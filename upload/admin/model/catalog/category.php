<?php
class ModelCatalogCategory extends Model {
	public function addCategory($data) {
		$this->db->query("INSERT INTO category SET image = '" . $this->db->escape(basename($data['image'])) . "', parent_id = '" . (int)$data['parent_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW(), date_added = NOW()");
	
		$category_id = $this->db->getLastId();
		
		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_description = '" . $this->db->escape(@$value['meta_description']) . "'");
		}
			
		$this->cache->delete('category');
	}
	
	public function editCategory($category_id, $data) {
		$this->db->query("UPDATE category SET image = '" . $this->db->escape(basename($data['image'])) . "', parent_id = '" . (int)$data['parent_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");

		$this->db->query("DELETE FROM category_description WHERE category_id = '" . (int)$category_id . "'");

		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', meta_description = '" . $this->db->escape(@$value['meta_description']) . "'");
		}

		$this->cache->delete('category');
	}
	
	public function deleteCategory($category_id) {
		$this->db->query("DELETE FROM category WHERE category_id = '" . (int)$category_id . "'");
		$this->db->query("DELETE FROM category_description WHERE category_id = '" . (int)$category_id . "'");
		
		$query = $this->db->query("SELECT category_id FROM category WHERE parent_id = '" . (int)$category_id . "'");

		foreach ($query->rows as $result) {
			$this->deleteCategory($result['category_id']);
		}
		
		$this->cache->delete('category');
	}

	public function getCategory($category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM category WHERE category_id = '" . (int)$category_id . "'");
		
		return $query->row;
	} 
	
	public function getCategories($parent_id) {
		$category_data = $this->cache->get('category.' . $this->language->getId() . '.' . $parent_id);
	
		if (!$category_data) {
			$category_data = array();
		
			$query = $this->db->query("SELECT * FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->language->getId() . "' ORDER BY c.sort_order, cd.name ASC");
		
			foreach ($query->rows as $result) {
				$category_data[] = array(
					'category_id' => $result['category_id'],
					'name'        => $this->getPath($result['category_id'], $this->language->getId()),
					'sort_order'  => $result['sort_order']
				);
			
				$category_data = array_merge($category_data, $this->getCategories($result['category_id']));
			}	
	
			$this->cache->set('category.' . $this->language->getId() . '.' . $parent_id, $category_data);
		}
		
		return $category_data;
	}
	
	public function getPath($category_id) {
		$query = $this->db->query("SELECT name, parent_id FROM category c LEFT JOIN category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->language->getId() . "' ORDER BY c.sort_order, cd.name ASC");
		
		$category_info = $query->row;
		
		if ($category_info['parent_id']) {
			return $this->getPath($category_info['parent_id'], $this->language->getId()) . $this->language->get('text_separator') . $category_info['name'];
		} else {
			return $category_info['name'];
		}
	}
	
	public function getCategoryDescriptions($category_id) {
		$category_description_data = array();
		
		$query = $this->db->query("SELECT * FROM category_description WHERE category_id = '" . (int)$category_id . "'");
		
		foreach ($query->rows as $result) {
			$category_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_description' => $result['meta_description']
			);
		}
		
		return $category_description_data;
	}	
		
	public function getTotalCategories() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM category");
		
		return $query->row['total'];
	}	
		
	public function getTotalCategoriesByImageId($image_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM category WHERE image_id = '" . (int)$image_id . "'");
		
		return $query->row['total'];
	}
}
?>