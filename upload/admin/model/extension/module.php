<?php
class ModelExtensionModule extends Model {
	public function editModule($code, $data = array()) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "modification WHERE code = '" . $this->db->escape($code) . "'");

		foreach ($data as $module) {
			if ($module['module_id']) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "modification SET module_id = '" . (int)$module['module_id'] . "', code = '" . $this->db->escape($code) . "', setting = '" . $this->db->escape(serialize($module)) . "'");
			} else {
				$this->db->query("UPDATE " . DB_PREFIX . "modification SET code = '" . $this->db->escape($module['code']) . "', setting = '" . $this->db->escape(serialize($module)) . "'");
			}
		}
	}	
	
	public function deleteModuleByCode($code) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "modification WHERE code = '" . $this->db->escape($code) . "'");
	}	
	
	public function getModulesByCode($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE code = '" . $this->db->escape($code) . "'");

		return $query->rows;
	}
		
	public function getModules() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module");

		return $query->rows;
	}
}