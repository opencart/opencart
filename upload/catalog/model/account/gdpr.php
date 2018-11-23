<?php
class ModelAccountGdpr extends Model {
	public function addGdpr($token, $email, $action) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_gdpr` SET `token` = '" . $this->db->escape($token) . "', `email` = '" . $this->db->escape($email) . "', `action` = '" . $this->db->escape($action) . "', `date_added` = NOW()");
	}

	public function getGdpr($token) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_gdpr` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getGdprByCode($token) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_gdpr` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return $query->row;
	}
}