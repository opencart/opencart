<?php
class ModelAccountApi extends Model {
	public function login($username, $password) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api` WHERE username = '" . $this->db->escape($username) . "' AND password = '" . $this->db->escape($password) . "' AND status = '1'");

		return $query->row;
	}
	
	public function getApiByToken($token) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "api` WHERE token = '" . $this->db->escape($token) . "' AND token != ''");

		$this->db->query("UPDATE " . DB_PREFIX . "api SET token = ''");

		return $query->row;
	}	
}