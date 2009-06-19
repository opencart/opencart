<?php
class ModelSettingExtension extends Model {
	public function getInstalled($type) {
		$extension_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");
		
		foreach ($query->rows as $result) {
			$extension_data[] = $result['key'];
		}
		
		return $extension_data;
	}
	
	public function install($type, $key) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "extension SET `type` = '" . $this->db->escape($type) . "', `key` = '" . $this->db->escape($key) . "'");
	}
	
	public function uninstall($type, $key) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' AND `key` = '" . $this->db->escape($key) . "'");
	}
}
?>