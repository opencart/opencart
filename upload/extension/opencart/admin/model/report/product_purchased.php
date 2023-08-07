<?php
namespace Opencart\Admin\Model\Extension\Opencart\Report;
/**
 * Class ProductPurchased
 *
 * @package Opencart\Admin\Model\Extension\Opencart\Report
 */
class ProductPurchased extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getPurchased(array $data = []): array {
		$sql = "SELECT op.`name`, op.`model`, SUM(op.`quantity`) AS quantity, SUM((op.`price` + op.`tax`) * op.`quantity`) AS `total` FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.`order_id` = o.`order_id`)";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.`order_status_id` = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.`order_status_id` > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		$sql .= " GROUP BY op.`product_id` ORDER BY total DESC";

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
	 * @param array $data
	 *
	 * @return int
	 */
	public function getTotalPurchased(array $data = []): int {
		$sql = "SELECT COUNT(DISTINCT op.`product_id`) AS `total` FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.`order_id` = o.`order_id`)";

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.`order_status_id` = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.`order_status_id` > '0'";
		}

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}
}
