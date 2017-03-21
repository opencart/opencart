<?php
class ModelSettingApi extends Model {
	public function login($username, $key) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "api a LEFT JOIN " . DB_PREFIX . "api_ip `ai` ON (a.api_id = ai.api_id) WHERE a.username = '" . $this->db->escape($username) . "' AND a.key = '" . $this->db->escape($key) . "'");

		return $query->row;
	}
}