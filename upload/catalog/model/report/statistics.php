<?php
namespace Opencart\Catalog\Model\Report;
/**
 * Class Statistics
 *
 * Can be called using $this->load->model('report/statistics');
 *
 * @package Opencart\Catalog\Model\Report
 */
class Statistics extends \Opencart\System\Engine\Model {
	/**
	 * Get Statistics
	 *
	 * Get the record of the statistics records in the database.
	 *
	 * @return array<int, array<string, mixed>> statistic records
	 *
	 * @example
	 *
	 * $this->load->model('report/statistics');
	 *
	 * $results = $this->model_report_statistics->getStatistics();
	 */
	public function getStatistics(): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "statistics`");

		return $query->rows;
	}

	/**
	 * Get Value
	 *
	 * @param string $code
	 *
	 * @return float
	 *
	 * @example
	 *
	 * $this->load->model('report/statistics');
	 *
	 * $value = $this->model_report_statistics->getValue($code);
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
	 * Add Value
	 *
	 * @param string $code
	 * @param float  $value
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('report/statistics');
	 *
	 * $this->model_report_statistics->addValue($code, $value);
	 */
	public function addValue(string $code, float $value): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "statistics` SET `value` = (`value` + '" . (float)$value . "') WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	/**
	 * Remove Value
	 *
	 * @param string $code
	 * @param float  $value
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('report/statistics');
	 *
	 * $this->model_report_statistics->removeValue($code, $value);
	 */
	public function removeValue(string $code, float $value): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "statistics` SET `value` = (`value` - '" . (float)$value . "') WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	/**
	 * Edit Value
	 *
	 * @param string $code
	 * @param float  $value
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('report/statistics');
	 *
	 * $this->model_report_statistics->editValue($code, $value);
	 */
	public function editValue(string $code, float $value): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "statistics` SET `value` = '" . (float)$value . "' WHERE `code` = '" . $this->db->escape($code) . "'");
	}
}
