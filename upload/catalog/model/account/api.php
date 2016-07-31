<?php
class ModelAccountApi extends Model {
	public function getApiByKey($key) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api` WHERE `key` = '" . $this->db->escape($key) . "' AND status = '1'");

		return $query->row;
	}

	public function addApiSession($api_id, $session_id, $ip) {
		$token = token(32);

		$this->db->query("INSERT INTO `" . DB_PREFIX . "api_session` SET api_id = '" . (int)$api_id . "', token = '" . $this->db->escape($token) . "', session_id = '" . $this->db->escape($session_id) . "', ip = '" . $this->db->escape($ip) . "', date_added = NOW(), date_modified = NOW()");

		return $token;
	}

	public function getApiIps($api_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api_ip` WHERE api_id = '" . (int)$api_id . "'");

		return $query->rows;
	}
}
