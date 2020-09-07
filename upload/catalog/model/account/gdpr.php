<?php
namespace Opencart\Application\Model\Account;
class Gdpr extends \Opencart\System\Engine\Model {
	public function addGdpr($code, $email, $action) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "gdpr` SET `store_id` = '" . $this->db->escape($this->config->get('config_store_id')) . "', `language_id` = '" . $this->db->escape($this->config->get('config_language_id')) . "', `code` = '" . $this->db->escape($code) . "', `email` = '" . $this->db->escape($email) . "', `action` = '" . $this->db->escape($action) . "', `date_added` = NOW()");
	}

	public function editStatus($code, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "gdpr` SET status = '" . (int)$status . "' WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	public function getGdprByCode($code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function getGdprsByEmail($email) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "gdpr` WHERE `email` = '" . $this->db->escape($email) . "'");

		return $query->rows;
	}
}