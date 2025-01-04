<?php
namespace Opencart\Admin\Model\Extension\Opencart\Report;
/**
 * Class Product Viewed
 *
 * Can be called from $this->load->model('extension/opencart/report/product_viewed');
 *
 * @package Opencart\Admin\Model\Extension\Opencart\Report
 */
class ProductViewed extends \Opencart\System\Engine\Model {
	/**
	 * Install
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->model_extension_opencart_report_product_viewed->install();
	 */
	public function install(): void {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_viewed` (
		  `product_id` INT(11) NOT NULL,
		  `viewed` INT(11) NOT NULL,
		  PRIMARY KEY (`product_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
	}

	/**
	 * Uninstall
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->model_extension_opencart_report_product_viewed->uninstall();
	 */
	public function uninstall(): void {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_viewed`");
	}

	/**
	 * Add Report
	 *
	 * @param int $product_id primary key of the product record
	 * @param int $viewed
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->model_extension_opencart_report_product_viewed->addReport($product_id, $viewed);
	 */
	public function addReport(int $product_id, int $viewed): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "product_viewed` SET `product_id` = '" . (int)$product_id . "', `viewed` = '" . (int)$viewed . "'");
	}

	/**
	 * Get Viewed
	 *
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_report_product_viewed->getViewed();
	 */
	public function getViewed(int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT `product_id`, `viewed` FROM `" . DB_PREFIX . "product_viewed` ORDER BY `viewed` DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * Get Total Viewed
	 *
	 * @return int total number of viewed records
	 *
	 * @example
	 *
	 * $viewed_total = $this->model_extension_opencart_report_product_viewed->getTotalViewed();
	 */
	public function getTotalViewed(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "product_viewed`");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total
	 *
	 * @return int total number of viewed records
	 *
	 * @example
	 *
	 * $viewed_total = $this->model_extension_opencart_report_product_viewed->getTotal();
	 */
	public function getTotal(): int {
		$query = $this->db->query("SELECT SUM(`viewed`) AS `total` FROM `" . DB_PREFIX . "product_viewed`");

		return (int)$query->row['total'];
	}

	/**
	 * Clear
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->model_extension_opencart_report_product_viewed->clear();
	 */
	public function clear(): void {
		$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "product_viewed`");
	}
}
