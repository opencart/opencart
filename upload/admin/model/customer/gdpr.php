<?php
class ModelCustomerGdpr extends Model {
	public function getGdprs($data = array()) {
		$sql = "SELECT *, CONCAT(c.`firstname`, ' ', c.`lastname`) AS customer, cgd.`name` AS customer_group, cg.`status`, cg.date_added FROM `" . DB_PREFIX . "customer_gdpr` cg LEFT JOIN `" . DB_PREFIX . "customer` c ON (cg.`customer_id` = c.`customer_id`) LEFT JOIN `" . DB_PREFIX . "customer_group_description` cgd ON (c.`customer_group_id` = cgd.`customer_group_id`) WHERE cgd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(c.`firstname`, ' ', c.`lastname`) LIKE '%" . $this->db->escape((string)$data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$sql .= " AND c.`email` LIKE '" . $this->db->escape((string)$data['filter_email']) . "%'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$sql .= " AND c.`customer_group_id` = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_status'])) {
			$sql .= " AND cg.`status` = '" . $this->db->escape((string)$data['filter_status']) . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(c.`date_added`) = DATE('" . $this->db->escape((string)$data['filter_date_added']) . "')";
		}

		$sql .= " ORDER BY c.`date_added` DESC";

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

	public function getExpires() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_gdpr` WHERE `status` = '1' AND DATE(`date_added`) <= DATE('" . $this->db->escape(date('Y-m-d', strtotime('+' . (int)$this->config->get('config_gdpr_limit') . ' days'))) . "') ORDER BY `date_added` DESC");

		return $query->rows;
	}

	public function getGdpr($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_gdpr` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getTotalGdprs($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_gdpr` cg LEFT JOIN `" . DB_PREFIX . "customer` c ON (cg.`customer_id` = c.`customer_id`)";

		$implode = array();

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(c.`firstname`, ' ', c.`lastname`) LIKE '%" . $this->db->escape((string)$data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "c.`email` LIKE '" . $this->db->escape((string)$data['filter_email']) . "%'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "c.`customer_group_id` = '" . (int)$data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_status'])) {
			$implode[] = "cg.`status` = '" . $this->db->escape((string)$data['filter_status']) . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(cg.`date_added`) = DATE('" . $this->db->escape((string)$data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function approveGdpr($customer_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer_gdpr` SET status = '1' WHERE customer_id = '" . (int)$customer_id . "'");
	}

	public function denyGdpr($customer_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer_gdpr` SET status = '0' WHERE customer_id = '" . (int)$customer_id . "'");
	}

	public function deleteGdpr($customer_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_gdpr` WHERE customer_id = '" . (int)$customer_id . "'");
	}
}