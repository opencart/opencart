<?php
class ModelExtensionModule extends Model {
	public function addModule($code, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "module WHERE code = '" . $this->db->escape($code) . "'");
		
		if ($data['module_id']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "module SET module_id = '" . $data['module_id'] . "', `code` = '" . $this->db->escape($code) . "', `setting` = '" . $this->db->escape(serialize($setting)) . "'");
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "module SET `code` = '" . $this->db->escape($code) . "', `setting` = '" . $this->db->escape(serialize($setting)) . "'");
		}
	}	
	
	public function deleteModules($code) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "module WHERE code = '" . $this->db->escape($code) . "'");
	}	
	
	public function getModules($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE code = '" . $this->db->escape($code) . "'");
		
		return $query->rows;
	}
}