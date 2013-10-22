<?php
class ModelCatalogImage extends Model {
	public function addImage($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "image SET filename = '" . $this->db->escape($data['filename']) . "', tag = '" . $this->db->escape($data['tag']) . "', date_added = NOW(), date_modified = NOW()");

      	$image_id = $this->db->getLastId(); 

      	foreach ($data['image_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO " . DB_PREFIX . "image_description SET image_id = '" . (int)$image_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
      	}	
	}
	
	public function editImage($image_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "image SET filename = '" . $this->db->escape($data['filename']) . "', tag = '" . $this->db->escape($data['tag']) . "', date_modified = NOW() WHERE image_id = '" . (int)$image_id . "'");

      	$this->db->query("DELETE FROM " . DB_PREFIX . "image_description WHERE image_id = '" . (int)$image_id . "'");

      	foreach ($data['image_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO " . DB_PREFIX . "image_description SET image_id = '" . (int)$image_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
      	}
	}
	
	public function deleteImage($image_id) {
      	$this->db->query("DELETE FROM " . DB_PREFIX . "image WHERE image_id = '" . (int)$image_id . "'");
	  	$this->db->query("DELETE FROM " . DB_PREFIX . "image_description WHERE image_id = '" . (int)$image_id . "'");	
	}	

	public function getImage($image_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "image i LEFT JOIN " . DB_PREFIX . "image_description id ON (i.image_id = id.image_id) WHERE i.image_id = '" . (int)$image_id . "' AND id.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}

	public function getImages($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "image i LEFT JOIN " . DB_PREFIX . "image_description id ON (i.image_id = id.image_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND (id.name LIKE '%" . $this->db->escape($data['filter_name']) . "%' OR i.filename LIKE '" . $this->db->escape($data['filter_name']) . "%' OR i.tag LIKE '%" . $this->db->escape($data['filter_name']) . "%')";
		}
		
		$sort_data = array(
			'id.name',
			'i.date_added',
			'i.date_modified'
		);
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY id.name";	
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
	
	public function getImageDescriptions($image_id) {
		$image_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "image_description WHERE image_id = '" . (int)$image_id . "'");
		
		foreach ($query->rows as $result) {
			$image_description_data[$result['language_id']] = array('name' => $result['name']);
		}
		
		return $image_description_data;
	}
	
	public function getTotalImages() {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "image";
		
		if (!empty($data['filter_name'])) {
			$sql .= "WHERE id.name LIKE '%" . $this->db->escape($data['filter_name']) . "%' OR i.filename LIKE '" . $this->db->escape($data['filter_name']) . "%' OR i.tag LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

      	$query = $this->db->query($sql);
		
		return $query->row['total'];
	}	
}
?>