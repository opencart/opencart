<?php
class ModelLocalisationLanguage extends Model {
	public function addLanguage($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "language SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', locale = '" . $this->db->escape($data['locale']) . "', directory = '" . $this->db->escape($data['directory']) . "', filename = '" . $this->db->escape($data['filename']) . "', image = '" . $this->db->escape($data['image']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "'");

		$this->cache->delete('language');
	}
	
	public function editLanguage($language_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "language SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', locale = '" . $this->db->escape($data['locale']) . "', directory = '" . $this->db->escape($data['directory']) . "', filename = '" . $this->db->escape($data['filename']) . "', image = '" . $this->db->escape($data['image']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "' WHERE language_id = '" . (int)$language_id . "'");
				
		$this->cache->delete('language');
	}
	
	public function deleteLanguage($language_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");
	
		$this->cache->delete('language');
	}
	
	public function getLanguage($language_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");
	
		return $query->row;
	}

	public function getLanguages($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "language";
	
			$sort_data = array(
				'name',
				'code',
				'sort_order'
			);	
			
			if (in_array(@$data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY sort_order, name";	
			}
			
			if (@$data['order'] == 'DESC') {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
	
			return $query->rows;
		} else {
			$language_data = $this->cache->get('language');
		
			if (!$language_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name");
	
    			foreach ($query->rows as $result) {
      				$language_data[$result['code']] = array(
        				'language_id' => $result['language_id'],
        				'name'        => $result['name'],
        				'code'        => $result['code'],
						'locale'      => $result['locale'],
						'image'       => $result['image'],
						'directory'   => $result['directory'],
						'filename'    => $result['filename'],
						'sort_order'  => $result['sort_order'],
						'status'      => $result['status']
      				);
    			}	
			
				$this->cache->set('language', $language_data);
			}
		
			return $language_data;			
		}
	}

	public function getTotalLanguages() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "language");
		
		return $query->row['total'];
	}
}
?>