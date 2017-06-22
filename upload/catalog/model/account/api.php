<?php
class ModelAccountApi extends Model {
	public function login($username, $key) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api` WHERE `username` = '" . $this->db->escape($username) . "' `key` = '" . $this->db->escape($key) . "' AND status = '1'");

		return $query->row;
	}

	public function addApiSession($api_id, $session_id, $ip) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "api_session` SET api_id = '" . (int)$api_id . "', session_id = '" . $this->db->escape($session_id) . "', ip = '" . $this->db->escape($ip) . "', date_added = NOW(), date_modified = NOW()");

		return $this->db->getLastId();
	}

	public function getApiIps($api_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api_ip` WHERE api_id = '" . (int)$api_id . "'");

		return $query->rows;
	}
}
