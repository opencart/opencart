<?php
namespace Opencart\System\Library\Cart;
/**
 * Class Weight
 *
 * @package Opencart\System\Library\Cart
 */
class Weight {
	/**
	 * @var object
	 */
	private object $db;
	/**
	 * @var object
	 */
	private object $config;
	/**
	 * @var array<int, array<string, mixed>>
	 */
	private array $weights = [];

	/**
	 * Constructor
	 *
	 * @param \Opencart\System\Engine\Registry $registry
	 */
	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->db = $registry->get('db');
		$this->config = $registry->get('config');

		$weight_class_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "weight_class` `wc` LEFT JOIN `" . DB_PREFIX . "weight_class_description` `wcd` ON (`wc`.`weight_class_id` = `wcd`.`weight_class_id`) WHERE `wcd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($weight_class_query->rows as $result) {
			$this->weights[$result['weight_class_id']] = [
				'weight_class_id' => $result['weight_class_id'],
				'title'           => $result['title'],
				'unit'            => $result['unit'],
				'value'           => $result['value']
			];
		}
	}

	/**
	 * Convert
	 *
	 * @param float $value
	 * @param int   $from
	 * @param int   $to
	 *
	 * @return float
	 *
	 * @example
	 *
	 * $weight = $this->weight->convert($value, $from, $to);
	 */
	public function convert(float $value, int $from, int $to): float {
		if ($from == $to) {
			return $value;
		}

		if (isset($this->weights[$from])) {
			$from = $this->weights[$from]['value'];
		} else {
			$from = 1;
		}

		if (isset($this->weights[$to])) {
			$to = $this->weights[$to]['value'];
		} else {
			$to = 1;
		}

		return $value * ($to / $from);
	}

	/**
	 * Format
	 *
	 * @param float  $value
	 * @param int    $weight_class_id primary key of the weight class record
	 * @param string $decimal_point
	 * @param string $thousand_point
	 *
	 * @return string
	 *
	 * @example
	 *
	 * $weight = $this->weight->format($value, $weight_class_id, $decimal_point, $thousand_point);
	 */
	public function format(float $value, int $weight_class_id, string $decimal_point = '.', string $thousand_point = ','): string {
		if (isset($this->weights[$weight_class_id])) {
			return number_format($value, 2, $decimal_point, $thousand_point) . $this->weights[$weight_class_id]['unit'];
		} else {
			return number_format($value, 2, $decimal_point, $thousand_point);
		}
	}

	/**
	 * Get Unit
	 *
	 * @param int $weight_class_id primary key of the weight class record
	 *
	 * @return string
	 *
	 * @example
	 *
	 * $unit = $this->weight->getUnit($weight_class_id);
	 */
	public function getUnit(int $weight_class_id): string {
		if (isset($this->weights[$weight_class_id])) {
			return $this->weights[$weight_class_id]['unit'];
		} else {
			return '';
		}
	}
}
