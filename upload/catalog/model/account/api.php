<?php
class ModelAccountApi extends Model {
	public function login($username, $password) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api` WHERE username = '" . $this->db->escape($username) . "' AND password = '" . $this->db->escape($password) . "' AND status = '1'");

		return $query->row;
	}
	
	public function addApiSession($api_id, $session_name, $session_id, $ip) {
		$token = token(64);
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "api_session` SET api_id = '" . (int)$api_id . "', name = '" . $this->db->escape($session_name) . "', ip = '" . $this->db->escape($session_name) . "', status = '1'");
		
		return $token;
	}
}