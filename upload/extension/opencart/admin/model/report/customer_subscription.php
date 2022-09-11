<?php
namespace Opencart\Admin\Model\Extension\Opencart\Report;
class CustomerSubscription extends \Opencart\System\Engine\Model {
	public function getTransactions(array $data = []): array {
		$sql = "SELECT `s`.`subscription_id`, `s`.`customer_id`, CONCAT(c.`firstname`, ' ', c.`lastname`) AS customer, c.`email`, cgd.`name` AS customer_group, c.`status`, SUM(st.`amount`) AS `total` FROM `" . DB_PREFIX . "subscription_transaction` st LEFT JOIN `" . DB_PREFIX . "subscription` `s` ON (st.`subscription_id` = `s`.`subscription_id`) LEFT JOIN `" . DB_PREFIX . "customer` c ON (`s`.`customer_id` = c.`customer_id`) LEFT JOIN `" . DB_PREFIX . "customer_group_description` cgd ON (c.`customer_group_id` = cgd.`customer_group_id`) WHERE cgd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(st.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(st.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(c.`firstname`, ' ', c.`lastname`) LIKE '" . $this->db->escape((string)$data['filter_customer']) . "'";
		}

		$sql .= " GROUP BY `s`.`customer_id` ORDER BY total DESC";

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

	public function getTotalTransactions(array $data = []): int {
		$sql = "SELECT COUNT(DISTINCT `s`.`customer_id`) AS `total` FROM `" . DB_PREFIX . "subscription_transaction` st LEFT JOIN `" . DB_PREFIX . "subscription` s ON (st.`subscription_id` = `s`.`subscription_id`) LEFT JOIN `" . DB_PREFIX . "customer` c ON (`s`.`customer_id` = c.`customer_id`)";

		$implode = [];

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(st.`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_start']) . "')";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(st.`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_end']) . "')";
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
