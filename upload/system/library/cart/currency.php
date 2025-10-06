<?php
namespace Opencart\System\Library\Cart;
/**
 * Class Currency
 *
 * @package Opencart\System\Library\Cart
 */
class Currency {
	/**
	 * @var object
	 */
	private object $db;
	/**
	 * @var object
	 */
	private object $language;
	/**
	 * @var array<string, array<string, mixed>>
	 */
	private array $currencies = [];

	/**
	 * Constructor
	 *
	 * @param \Opencart\System\Engine\Registry $registry
	 */
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

	/**
	 * Format
	 *
	 * @param float  $number
	 * @param string $currency
	 * @param float  $value
	 * @param bool   $format
	 *
	 * @return float|string
	 *
	 * @example
	 *
	 * $currency = $this->currency->format($number, $currency, $value, $format);
	 */
	public function format(float $number, string $currency, float $value = 0, bool $format = true) {
		if (!isset($this->currencies[$currency])) {
			return '';
		}

		$symbol_left = $this->currencies[$currency]['symbol_left'];
		$symbol_right = $this->currencies[$currency]['symbol_right'];
		$decimal_place = $this->currencies[$currency]['decimal_place'];

		if (!$value) {
			$value = $this->currencies[$currency]['value'];
		}

		$number = round($number, $decimal_place);

		$amount = $value ? (float)$number * $value : (float)$number;

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

	/**
	 * Convert
	 *
	 * @param float  $value
	 * @param string $from
	 * @param string $to
	 *
	 * @return float
	 *
	 * @example
	 *
	 * $currency = $this->currency->convert($value, $from, $to);
	 */
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

	/**
	 * Get Id
	 *
	 * @param string $currency
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $currency_id = $this->currency->getId($currency);
	 */
	public function getId(string $currency): int {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['currency_id'];
		} else {
			return 0;
		}
	}

	/**
	 * Get Symbol Left
	 *
	 * @param string $currency
	 *
	 * @return string
	 *
	 * @example
	 *
	 * $symbol_left = $this->currency->getSymbolLeft($currency);
	 */
	public function getSymbolLeft(string $currency): string {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['symbol_left'];
		} else {
			return '';
		}
	}

	/**
	 * Get Symbol Right
	 *
	 * @param string $currency
	 *
	 * @return string
	 *
	 * @example
	 *
	 * $symbol_right = $this->currency->getSymbolRight($currency);
	 */
	public function getSymbolRight(string $currency): string {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['symbol_right'];
		} else {
			return '';
		}
	}

	/**
	 * Get Decimal Place
	 *
	 * @param string $currency
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $decimal_place = $this->currency->getDecimalPlace($currency);
	 */
	public function getDecimalPlace(string $currency): int {
		if (isset($this->currencies[$currency])) {
			return (int)$this->currencies[$currency]['decimal_place'];
		} else {
			return 0;
		}
	}

	/**
	 * Get Value
	 *
	 * @param string $currency
	 *
	 * @return float
	 *
	 * @example
	 *
	 * $value = $this->currency->getValue($currency);
	 */
	public function getValue(string $currency): float {
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['value'];
		} else {
			return 0;
		}
	}

	/**
	 * Has
	 *
	 * @param string $currency
	 *
	 * @return bool
	 *
	 * $currency = $this->currency->has($currency);
	 */
	public function has(string $currency): bool {
		return isset($this->currencies[$currency]);
	}
}
