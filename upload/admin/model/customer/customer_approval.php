<?php
class ModelCustomerCustomerApproval extends Model {
	public function getCustomerApprovals($data = array()) {
		$sql = "SELECT *, CONCAT(c.`firstname`, ' ', c.`lastname`) AS name, cgd.`name` AS customer_group, ca.`type` FROM `" . DB_PREFIX . "customer_approval` ca LEFT JOIN `" . DB_PREFIX . "customer` c ON (ca.`customer_id` = c.`customer_id`) LEFT JOIN `" . DB_PREFIX . "customer_group_description` cgd ON (c.`customer_group_id` = cgd.`customer_group_id`) WHERE cgd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND CONCAT(c.`firstname`, ' ', c.`lastname`) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$sql .= " AND c.`email` LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}
		
		if (!empty($data['filter_customer_group_id'])) {
			$sql .= " AND c.`customer_group_id` = '" . (int)$data['filter_customer_group_id'] . "'";
		}
		
		if (!empty($data['filter_type'])) {
			$sql .= " AND ca.`type` = '" . $this->db->escape($data['filter_type']) . "'";
		}
		
		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(c.`date_added`) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
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
	
	public function getCustomerApproval($customer_approval_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_approval` WHERE `customer_approval_id` = '" . (int)$customer_approval_id . "'");
		
		return $query->row;
	}
	
	public function getTotalCustomerApprovals($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_approval` ca LEFT JOIN `" . DB_PREFIX . "customer` c ON (ca.`customer_id` = c.`customer_id`)";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.`firstname`, ' ', c.`lastname`) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "c.`email` LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "c.`customer_group_id` = '" . (int)$data['filter_customer_group_id'] . "'";
		}
		
		if (!empty($data['filter_type'])) {
			$implode[] = "ca.`type` = '" . $this->db->escape($data['filter_type']) . "'";
		}
		
		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(ca.`date_added`) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function approveCustomer($customer_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET status = '1' WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_approval` WHERE customer_id = '" . (int)$customer_id . "' AND `type` = 'customer'");
	}

	public function denyCustomer($customer_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_approval` WHERE customer_id = '" . (int)$customer_id . "' AND `type` = 'customer'");
	}

	public function approveAffiliate($customer_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer_affiliate` SET status = '1' WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_approval` WHERE customer_id = '" . (int)$customer_id . "' AND `type` = 'affiliate'");
	}
	
	public function denyAffiliate($customer_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_approval` WHERE customer_id = '" . (int)$customer_id . "' AND `type` = 'affiliate'");
	}	
}