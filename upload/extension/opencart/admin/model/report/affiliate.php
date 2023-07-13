<?php
namespace Opencart\Admin\Model\Extension\Opencart\Report;
class Affiliate extends \Opencart\System\Engine\Model {
	public function getAffiliates(array $data = []): array {
		$sql = "SELECT c.`customer_id`, CONCAT(c.`firstname`, ' ', c.`lastname`) AS `customer`, c.`email`, cgd.`name` AS `customer_group`, c.`status`, o.`order_id`, SUM(op.`quantity`) AS `products`, o.`total` AS `total` 
FROM `" . DB_PREFIX . "order` o 
LEFT JOIN `" . DB_PREFIX . "order_product` op ON (o.`order_id` = op.`order_id`) 
LEFT JOIN `" . DB_PREFIX . "customer` c ON (o.`customer_id` = c.`customer_id`) 
LEFT JOIN `" . DB_PREFIX . "customer_group_description` cgd ON (c.`customer_group_id` = cgd.`customer_group_id`) 
WHERE o.`customer_id` > '0' AND cgd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(c.`firstname`, ' ', c.`lastname`) LIKE '" . $this->db->escape((string)$data['filter_customer']) . "'";
		}

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.`order_status_id` = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.`order_status_id` > '0'";
		}

		$sql .= " GROUP BY o.`order_id`";

		$sql = "SELECT t.`customer_id`, t.`customer`, t.`email`, t.`customer_group`, t.`status`, COUNT(DISTINCT t.`order_id`) AS `orders`, SUM(t.`products`) AS `products`, SUM(t.`total`) AS `total` FROM (" . $sql . ") AS t GROUP BY t.`customer_id` ORDER BY `total` DESC";

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

	public function getTotalAffiliates(array $data = []): int {
		$sql = "SELECT COUNT(DISTINCT o.`customer_id`) AS `total` FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "customer` c ON (o.`customer_id` = c.`customer_id`) WHERE o.`customer_id` > '0'";

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(c.`firstname`, ' ', c.`lastname`) LIKE '" . $this->db->escape((string)$data['filter_customer']) . "'";
		}

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.`order_status_id` = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.`order_status_id` > '0'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	public function getRewardPoints(array $data = []): array {
		$sql = "SELECT cr.`customer_id`, CONCAT(c.`firstname`, ' ', c.`lastname`) AS `customer`, c.`email`, cgd.`name` AS `customer_group`, c.`status`, SUM(cr.`points`) AS `points`, COUNT(o.`order_id`) AS `orders`, SUM(o.`total`) AS `total` FROM `" . DB_PREFIX . "customer_reward` cr LEFT JOIN `" . DB_PREFIX . "customer` c ON (cr.`customer_id` = c.`customer_id`) LEFT JOIN `" . DB_PREFIX . "customer_group_description` cgd ON (c.`customer_group_id` = cgd.`customer_group_id`) LEFT JOIN `" . DB_PREFIX . "order` o ON (cr.`order_id` = o.`order_id`) WHERE cgd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(cr.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(cr.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(c.`firstname`, ' ', c.`lastname`) LIKE '" . $this->db->escape((string)$data['filter_customer']) . "'";
		}

		$sql .= " GROUP BY cr.`customer_id` ORDER BY `points` DESC";

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

	public function getTotalRewardPoints(array $data = []): int {
		$sql = "SELECT COUNT(DISTINCT cr.`customer_id`) AS `total` FROM `" . DB_PREFIX . "customer_reward` cr LEFT JOIN `" . DB_PREFIX . "customer` c ON (cr.`customer_id` = c.`customer_id`)";

		$implode = [];

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(cr.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(cr.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(c.`firstname`, ' ', c.`lastname`) LIKE '" . $this->db->escape((string)$data['filter_customer']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}
}
