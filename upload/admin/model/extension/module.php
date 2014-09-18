<?php
class ModelExtensionModule extends Model {
	public function addModule($code, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "modification SET code = '" . $this->db->escape($data['code']) . "', date_added = NOW()");
	}

	public function deleteModule($code) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "modification WHERE code = '" . $this->db->escape($code) . "'");
	}	
	
	public function getModule($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE code = '" . $this->db->escape($code) . "'");

		return $query->rows;
	}
		
	public function getModules() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module");

		return $query->rows;
	}
}