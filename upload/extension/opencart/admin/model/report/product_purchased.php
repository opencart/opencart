<?php
namespace Opencart\Admin\Model\Extension\Opencart\Report;
/**
 * Class Product Purchased
 *
 * Can be called from $this->load->model('extension/opencart/report/product_purchased');
 *
 * @package Opencart\Admin\Model\Extension\Opencart\Report
 */
class ProductPurchased extends \Opencart\System\Engine\Model {
	/**
	 * Get Purchased
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $results = $this->model_extension_opencart_report_product_purchased->getPurchased();
	 */
	public function getPurchased(array $data = []): array {
		$sql = "SELECT `op`.`name`, `op`.`model`, SUM(`op`.`quantity`) AS `quantity`, SUM((`op`.`price` + `op`.`tax`) * `op`.`quantity`) AS `total` FROM `" . DB_PREFIX . "order_product` `op` LEFT JOIN `" . DB_PREFIX . "order` `o` ON (`op`.`order_id` = `o`.`order_id`)";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE `o`.`order_status_id` = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE `o`.`order_status_id` > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(`o`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(`o`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		$sql .= " GROUP BY `op`.`product_id` ORDER BY total DESC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * Get Total Purchased
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of purchased records
	 *
	 * @example
	 *
	 * $purchased_total = $this->model_extension_opencart_report_product_purchased->getTotalPurchased();
	 */
	public function getTotalPurchased(array $data = []): int {
		$sql = "SELECT COUNT(DISTINCT `op`.`product_id`) AS `total` FROM `" . DB_PREFIX . "order_product` `op` LEFT JOIN `" . DB_PREFIX . "order` `o` ON (`op`.`order_id` = `o`.`order_id`)";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE `o`.`order_status_id` = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE `o`.`order_status_id` > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(`o`.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(`o`.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}
}
