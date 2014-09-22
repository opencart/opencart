<?php
class ModelExtensionModule extends Model {
	public function addModule($code, $index) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "module WHERE code = '" . $this->db->escape($code) . "'");

		foreach ($data as $module) {
			if ($module['module_id']) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "module SET `module_id` = '" . (int)$module['module_id'] . "', `code` = '" . $this->db->escape($code) . "', `index` = '" . $this->db->escape($module) . "'");
			} else {
				$this->db->query("INSERT INTO " . DB_PREFIX . "module SET `code` = '" . $this->db->escape($code) . "', `setting` = '" . $this->db->escape(serialize($module)) . "'");
			}
		}
	}	
	
	public function deleteModuleByCode($code) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "module WHERE code = '" . $this->db->escape($code) . "'");
	}	
	
	public function getModulesByCode($code) {
		$module_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE code = '" . $this->db->escape($code) . "'");
		
		foreach ($query->rows as $result) {
			$module_data[$result['module_id']] = $result['code'];
		}
		
		return $module_data;
	}
		
	public function getModules() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module");
		
		return $query->rows;
	}
}