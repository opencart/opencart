<?php
namespace Opencart\Admin\Model\Extension\Opencart\Other;
/**
 * Class Cloud
 *
 * Can be called from $this->load->model('extension/opencart/other/cloud');
 *
 * @package Opencart\Admin\Model\Extension\Opencart\Other
 */
class Cloud extends \Opencart\System\Engine\Model {
	/**
	 * Install
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->model_extension_opencart_other_cloud->install();
	 */
	public function install(): void {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_bestseller` (
		  `product_id` int(11) NOT NULL,
		  `total` int(11) NOT NULL,
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
	 * $this->model_extension_opencart_other_cloud->uninstall();
	 */
	public function uninstall(): void {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_bestseller`");
	}

	/**
	 * Edit Total
	 *
	 * @param int $product_id primary key of the product record
	 * @param int $total
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->model_extension_opencart_other_cloud->editTotal($product_id, $total);
	 */
	public function editTotal(int $product_id, int $total): void {
		$this->db->query("REPLACE INTO `" . DB_PREFIX . "product_bestseller` SET `product_id` = '" . (int)$product_id . "', `total` = '" . (int)$total . "'");
	}

	/**
	 * Delete
	 *
	 * @param int $product_id primary key of the product record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->model_extension_opencart_other_cloud->delete($product_id);
	 */
	public function delete(int $product_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_bestseller` WHERE `product_id` = '" . (int)$product_id . "'");
	}

	/**
	 * Get Reports
	 *
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_other_cloud->getReports();
	 */
	public function getReports(int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_bestseller` ORDER BY `total` DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * Get Total Reports
	 *
	 * @return int total number of product bestseller report records
	 */
	public function getTotalReports(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "product_bestseller`");

		return (int)$query->row['total'];
	}
}
