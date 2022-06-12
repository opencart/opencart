<?php
namespace Opencart\System\Library\Cart;
class Currency {
	private array $currencies = [];

	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->db = $registry->get('db');
		$this->language = $registry->get('language');

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "currency`");

		foreach ($query->rows as $result) {
			$this->currencies[$result['code']] = [
				'currency_id'   => $result['currency_id'],
				'title'         => $result['title'],
				'symbol_left'   => $result['symbol_left'],
				'symbol_right'  => $result['symbol_right'],
				'decimal_place' => $result['decimal_place'],
				'value'         => $result['value']
			];
		}
	}

	public function format(float $number, string $currency, float $value = 0, bool $format = true): string {
		if (!isset($this->currencies[$currency])) {
			return '';
		}

		$symbol_left = $this->currencies[$currency]['symbol_left'];
		$symbol_right = $this->currencies[$currency]['symbol_right'];
		$decimal_place = $this->currencies[$currency]['decimal_place'];

		if (!$value) {
			$value = $this->currencies[$currency]['value'];
		}

		$amount = $value ? (float)$number * $value : (float)$number;
		
		$amount = round($amount, $decimal_place);
		
		if (!$format) {
			return $amount;
		}

		$string = '';

		if ($symbol_left) {
			$string .= $symbol_left;
		}

		$string .= number_format($amount, $decimal_place, $this->language->get('decimal_point'), $this->language->get('thousand_point'));

		if ($symbol_right) {
			$string .= $symbol_right;
		}

		return $string;
	}

	public function convert(float $value, string $from, string $to): float {
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
	
	public function getId(string $currency): int {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['currency_id'];
		} else {
			return 0;
		}
	}

	public function getSymbolLeft(string $currency): string {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['symbol_left'];
		} else {
			return '';
		}
	}

	public function getSymbolRight(string $currency): string {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['symbol_right'];
		} else {
			return '';
		}
	}

	public function getDecimalPlace(string $currency): string {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['decimal_place'];
		} else {
			return 0;
		}
	}

	public function getValue(string $currency): float {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['value'];
		} else {
			return 0;
		}
	}

	public function has(string $currency): bool {
		return isset($this->currencies[$currency]);
	}
}
