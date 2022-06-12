<?php
namespace Opencart\System\Library\Cart;
class Length {
	private array $lengths = [];

	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->db = $registry->get('db');
		$this->config = $registry->get('config');

		$length_class_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "length_class` mc LEFT JOIN `" . DB_PREFIX . "length_class_description` mcd ON (mc.`length_class_id` = mcd.`length_class_id`) WHERE mcd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($length_class_query->rows as $result) {
			$this->lengths[$result['length_class_id']] = [
				'length_class_id' => $result['length_class_id'],
				'title'           => $result['title'],
				'unit'            => $result['unit'],
				'value'           => $result['value']
			];
		}
	}

	public function convert(float $value, string $from, string $to): float {
		if ($from == $to) {
			return $value;
		}

		if (isset($this->lengths[$from])) {
			$from = $this->lengths[$from]['value'];
		} else {
			$from = 1;
		}

		if (isset($this->lengths[$to])) {
			$to = $this->lengths[$to]['value'];
		} else {
			$to = 1;
		}

		return $value * ($to / $from);
	}

	public function format(float $value, int $length_class_id, string $decimal_point = '.', string $thousand_point = ','): string {
		if (isset($this->lengths[$length_class_id])) {
			return number_format($value, 2, $decimal_point, $thousand_point) . $this->lengths[$length_class_id]['unit'];
		} else {
			return number_format($value, 2, $decimal_point, $thousand_point);
		}
	}

	public function getUnit(int $length_class_id): string {
		if (isset($this->lengths[$length_class_id])) {
			return $this->lengths[$length_class_id]['unit'];
		} else {
			return '';
		}
	}
}
