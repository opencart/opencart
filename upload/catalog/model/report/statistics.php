<?php
namespace Opencart\Catalog\Model\Report;
/**
 * Class Statistics
 *
 * @package Opencart\Catalog\Model\Report
 */
class Statistics extends \Opencart\System\Engine\Model {
	/**
	 * @return array
	 */
	public function getStatistics(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "statistics`");

		return $query->rows;
	}

	/**
	 * @param string $code
	 *
	 * @return float
	 */
	public function getValue(string $code): float {
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "statistics` WHERE `code` = '" . $this->db->escape($code) . "'");

		if ($query->num_rows) {
			return $query->row['value'];
		} else {
			return 0;
		}
	}

	/**
	 * @param string $code
	 * @param float  $value
	 *
	 * @return void
	 */
	public function addValue(string $code, float $value): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "statistics` SET `value` = (`value` + '" . (float)$value . "') WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	/**
	 * @param string $code
	 * @param float  $value
	 *
	 * @return void
	 */
	public function removeValue(string $code, float $value): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "statistics` SET `value` = (`value` - '" . (float)$value . "') WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	/**
	 * @param string $code
	 * @param float  $value
	 *
	 * @return void
	 */
	public function editValue(string $code, float $value): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "statistics` SET `value` = '" . (float)$value . "' WHERE `code` = '" . $this->db->escape($code) . "'");
	}	
}
