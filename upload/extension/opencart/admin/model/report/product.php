<?php
namespace Opencart\Admin\Model\Extension\Opencart\Report;
class Product extends \Opencart\System\Engine\Model {
	public function getViewed(array $data = []): array {
		$sql = "SELECT pd.`name`, p.`model`, COUNT(pr.`product_id`) AS `viewed` FROM `" . DB_PREFIX . "product_report` pr LEFT JOIN `" . DB_PREFIX . "product` p ON (pr.`product_id` = p.`product_id`) LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.`product_id` = pd.`product_id`) WHERE pd.`language_id` = '" . (int)$this->config->get('config_language_id') . "' GROUP BY pr.product_id ORDER BY p.`viewed` DESC";

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

	public function getTotalViewed(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "product_report`");

		return $query->row['total'];
	}

	public function reset(): void {
		$this->db->query("TRUNCATE `" . DB_PREFIX . "product_report`");
	}

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

		return $query->row['total'];
	}
}
