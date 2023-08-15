<?php
namespace Opencart\Admin\Model\Extension\Opencart\Module;
/**
 * Class Bestseller
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Module
 */
class Bestseller extends \Opencart\System\Engine\Model {
	/**
	 * @return void
	 */
	public function install(): void {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_bestseller` (
		  `product_id` int(11) NOT NULL,
		  `total` int(11) NOT NULL,
		  PRIMARY KEY (`product_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
	}

	/**
	 * @return void
	 */
	public function uninstall(): void {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_bestseller`");
	}

	/**
	 * @param int $product_id
	 * @param int $total
	 *
	 * @return void
	 */
	public function editTotal(int $product_id, int $total): void {
		$this->db->query("REPLACE INTO `" . DB_PREFIX . "product_bestseller` SET `product_id` = '" . (int)$product_id . "', `total` = '" . (int)$total . "'");
	}

	/**
	 * @param int $product_id
	 *
	 * @return void
	 */
	public function delete(int $product_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_bestseller` WHERE `product_id` = '" . (int)$product_id . "'");
	}

	/**
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array
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
	 * @return int
	 */
	public function getTotalReports(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "product_bestseller`");

		return (int)$query->row['total'];
	}
}
