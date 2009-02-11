<?php
class ModelCatalogImage extends Model {	
	public function addImage($data) {
	  	$this->db->query("INSERT INTO image SET filename = '" . $this->db->escape($data['image']['name']) . "', date_added = NOW()");

      	$image_id = $this->db->getLastId();

      	foreach ($data['image_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO image_description SET image_id = '" . (int)$image_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "'");
      	}
		
		$this->cache->delete('image');
	}
	
	public function editImage($image_id, $data) {
      	if ($data['image']['name']) {
        	$this->db->query("UPDATE image set filename = '" . $this->db->escape($data['image']['name']) . "' WHERE image_id = '" . (int)$image_id . "'");
      	}
 
      	$this->db->query("DELETE FROM image_description WHERE image_id = '" . (int)$image_id . "'");

      	foreach ($data['image_description'] as $language_id => $value) {
        	$this->db->query("INSERT INTO image_description SET image_id = '" . (int)$image_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "'");
      	}
		
		$this->cache->delete('image');	
	}
	
	public function deleteImage($image_id) {
      	$this->db->query("DELETE FROM image WHERE image_id = '" . (int)$image_id . "'");
	  	$this->db->query("DELETE FROM image_description WHERE image_id = '" . (int)$image_id . "'");	
		$this->db->query("DELETE FROM product_to_image WHERE image_id = '" . (int)$image_id . "'");
		
		$this->cache->delete('image');	
	}	
	
	public function getImage($image_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM image i LEFT JOIN image_description id ON (i.image_id = id.image_id) WHERE i.image_id = '" . (int)$image_id . "' AND id.language_id = '" . (int)$this->language->getId() . "'");
		
		return $query->row; 
	}	

	public function getImages($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM image i LEFT JOIN image_description id ON (i.image_id = id.image_id) WHERE id.language_id = '" . (int)$this->language->getId() . "'";
		
			if (isset($data['sort'])) {
				$sql .= " ORDER BY " . $this->db->escape($data['sort']);	
			} else {
				$sql .= " ORDER BY id.title";	
			}
			
			if (isset($data['order'])) {
				$sql .= " " . $this->db->escape($data['order']);
			} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
			$query = $this->db->query($sql);
			
			return $query->rows;		
		} else {
			$image = $this->cache->get('image.' . $this->language->getId());
		
			if (!$image) {
				$query = $this->db->query("SELECT * FROM image i LEFT JOIN image_description id ON (i.image_id = id.image_id) WHERE id.language_id = '" . (int)$this->language->getId() . "' ORDER BY id.title");
			
				$image = $query->rows;
			
				$this->cache->set('image.' . $this->language->getId(), $image);
			}
		
			return $image;				
		}
	}

	public function getImageDescriptions($image_id) {
		$image_description_data = array();
		
		$query = $this->db->query("SELECT * FROM image_description WHERE image_id = '" . (int)$image_id . "'");
		
		foreach ($query->rows as $result) {
			$image_description_data[$result['language_id']] = array('title' => $result['title']);
		}
		
		return $image_description_data;
	}
	
	public function getTotalImages() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM image i LEFT JOIN image_description id ON (i.image_id = id.image_id) WHERE id.language_id = '" . (int)$this->language->getId() . "'");
		
		return $query->row['total'];
	}
}
?>