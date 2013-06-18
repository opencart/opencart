<?php
class ModelSettingModification extends Model {
	public function addModification($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "modification SET name = '" . $this->db->escape($data['name']) . "', author = '" . $this->db->escape($data['author']) . "', version = '" . $this->db->escape($data['version']) . "', code = '" . $this->db->escape($data['code']) . "', status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW(), date_modified = NOW()");
	}
	
	public function editModification($modification_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "modification SET name = '" . $this->db->escape($data['name']) . "', author = '" . $this->db->escape($data['author']) . "', version = '" . $this->db->escape($data['version']) . "', code = '" . $this->db->escape($data['code']) . "', status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE modification_id = '" . $modification_id . "'");
	}
	
	public function deleteModification($modification_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "modification WHERE modification_id = '" . (int)$modification_id . "'");
	}
	
	public function getModification($modification_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "modification WHERE modification_id = '" . (int)$modification_id . "'");
		
		return $query->row;
	}	
	
	public function getModifications() {
		$sql = "SELECT * FROM " . DB_PREFIX . "modification";
								
		$sort_data = array(
			'code',
			'name',
			'author',
			'status',
			'sort_order',
			'date_added',
			'date_modified'
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY code";	
		}	
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
		
		$query = $this->db->query($sql);
		
		return $query->rows;	
	}	
	
	public function getTotalModifications() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "modification");
		
		return $query->row['total'];
	}		
}
?>