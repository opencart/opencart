<?php
class ModelSettingModification extends Model {
	public function addModification($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "modification SET name = '" . $this->db->escape($data['name']) . "', author = '" . $this->db->escape($data['author']) . "', code = '" . $this->db->escape($data['code']) . "', date_added = NOW(), date_modified = NOW()");
	}
	
	public function editModification() {
		$this->db->query("INSERT INTO " . DB_PREFIX . "modification SET name = '" . $this->db->escape($data['name']) . "', author = '" . $this->db->escape($data['author']) . "', code = '" . $this->db->escape($data['code']) . "', date_added = NOW(), date_modified = NOW()");
	}
	
	public function deleteModification($modification_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "modification WHERE modification_id = '" . (int)$modification_id . "'");
	}
	
	public function getModification($modification_id) {
	
	}	
	
	public function getModifications() {
	
	}			
}
?>