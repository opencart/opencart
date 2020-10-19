<?php
namespace Opencart\System\Library\Cart;
class Currency {
	private $currencies = [];

	public function __construct($registry) {
		$this->db = $registry->get('db');
		$this->language = $registry->get('language');
		$this->config = $registry->get('config');

		$query = $this->db->query("SELECT *, c2c.`country_id` AS country_id, c2cp.`country_id` AS `push` FROM `" . DB_PREFIX . "currency` c INNER JOIN `" . DB_PREFIX . "currency_description` cd ON (c.`currency_id` = cd.`currency_id`) INNER JOIN `" . DB_PREFIX . "currency_to_country c2c ON (c.`currency_id` = c2c.`currency_id`) LEFT JOIN `" . DB_PREFIX . "currency_to_country_push` c2cp ON (c.`currency_id` = c2cp.`currency_id`) WHERE c.`status` = '1' AND cd.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND c2c.`country_id` = '" . (int)$this->config->get('config_country_id') . "'");

		foreach ($query->rows as $result) {
			$this->currencies[$result['code']] = [
				'currency_id'   => $result['currency_id'],
				'title'         => $result['title'],
				'symbol_left'   => $result['symbol_left'],
				'symbol_right'  => $result['symbol_right'],
				'decimal_place' => $result['decimal_place'],
				'push'		=> ($result['push'] && ($result['push'] == $result['country_id']) ? 1 : 0),
				'value'         => $result['value']
			];
		}
	}

	public function format($number, $currency, $value = '', $format = true) {
		$symbol_left = $this->currencies[$currency]['symbol_left'];
		$symbol_right = $this->currencies[$currency]['symbol_right'];
		$decimal_place = $this->currencies[$currency]['decimal_place'];
		$push = $this->currencies[$currency]['push'];

		if (!$value) {
			$value = $this->currencies[$currency]['value'];
		}

		$amount = $value ? (float)$number * $value : (float)$number;
		
		$amount = round($amount, (int)$decimal_place);
		
		if (!$format) {
			return $amount;
		}

		$string = '';

		if ($symbol_left) {
			$string .= $symbol_left . ($push ? ' ' : '');
		}

		$string .= number_format($amount, (int)$decimal_place, $this->language->get('decimal_point'), $this->language->get('thousand_point'));

		if ($symbol_right) {
			$string .= ($push ? ' ' : '') . $symbol_right;
		}

		return $string;
	}

	public function convert($value, $from, $to) {
		if (isset($this->currencies[$from])) {
			$from = $this->currencies[$from]['value'];
		} else {
			$from = 1;
		}

		if (isset($this->currencies[$to])) {
			$to = $this->currencies[$to]['value'];
		} else {
			$to = 1;
		}

		return $value * ($to / $from);
	}
	
	public function getId($currency) {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['currency_id'];
		} else {
			return 0;
		}
	}

	public function getSymbolLeft($currency) {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['symbol_left'];
		} else {
			return '';
		}
	}

	public function getSymbolRight($currency) {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['symbol_right'];
		} else {
			return '';
		}
	}

	public function getDecimalPlace($currency) {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['decimal_place'];
		} else {
			return 0;
		}
	}

	public function getValue($currency) {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['value'];
		} else {
			return 0;
		}
	}

	public function has($currency) {
		return isset($this->currencies[$currency]);
	}
}
