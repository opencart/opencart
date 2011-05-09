<?php
class ModelDesignMenu extends Model {
	public function addMenu($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "menu SET store_id = '" . (int)$data['store_id'] . "', `link` = '" . $this->db->escape($data['link']) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "'");
	
		$menu_id = $this->db->getLastId();
	
		foreach ($data['menu_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int)$menu_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		if (isset($data['menu_link'])) {
			foreach ($data['menu_link'] as $menu_link) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "menu_link SET menu_id = '" . (int)$menu_id . "', `heading` = '" . (int)$menu_link['heading'] . "', `link` = '" .  $this->db->escape($menu_link['link']) . "', sort_order = '" .  $this->db->escape($menu_link['sort_order']) . "'");
				
				$menu_link_id = $this->db->getLastId();
				
				foreach ($menu_link['menu_link_description'] as $language_id => $menu_link_description) {				
					$this->db->query("INSERT INTO " . DB_PREFIX . "menu_link_description SET menu_link_id = '" . (int)$menu_link_id . "', language_id = '" . (int)$language_id . "', menu_id = '" . (int)$menu_id . "', name = '" .  $this->db->escape($menu_link_description['name']) . "'");
				}
			}
		}					
	}
	
	public function editMenu($menu_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "menu SET store_id = '" . (int)$data['store_id'] . "', `link` = '" . $this->db->escape($data['link']) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE menu_id = '" . (int)$menu_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_description WHERE menu_id = '" . (int)$menu_id . "'");
	
		foreach ($data['menu_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int)$menu_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}		
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_link WHERE menu_id = '" . (int)$menu_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_link_description WHERE menu_id = '" . (int)$menu_id . "'");
			
		if (isset($data['menu_link'])) {
			foreach ($data['menu_link'] as $menu_link) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "menu_link SET menu_id = '" . (int)$menu_id . "', `heading` = '" . (int)$menu_link['heading'] . "', `link` = '" .  $this->db->escape($menu_link['link']) . "', sort_order = '" .  $this->db->escape($menu_link['sort_order']) . "'");
				
				$menu_link_id = $this->db->getLastId();
				
				foreach ($menu_link['menu_link_description'] as $language_id => $menu_link_description) {				
					$this->db->query("INSERT INTO " . DB_PREFIX . "menu_link_description SET menu_link_id = '" . (int)$menu_link_id . "', language_id = '" . (int)$language_id . "', menu_id = '" . (int)$menu_id . "', name = '" .  $this->db->escape($menu_link_description['name']) . "'");
				}
			}
		}			
	}
	
	public function deleteMenu($menu_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu WHERE menu_id = '" . (int)$menu_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_description WHERE menu_id = '" . (int)$menu_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_link WHERE menu_id = '" . (int)$menu_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_link_description WHERE menu_id = '" . (int)$menu_id . "'");
	}
	
	public function getMenu($menu_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE m.menu_id = '" . (int)$menu_id . "' AND md.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
		
	public function getMenus($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON (m.menu_id = md.menu_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		$sort_data = array(
			'md.name',
			'store',
			'm.sort_order'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
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
	
	public function getMenuDescriptions($menu_id) {
		$menu_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_description WHERE menu_id = '" . (int)$menu_id . "'");
				
		foreach ($query->rows as $result) {
			$menu_data[$result['language_id']] = array('name' => $result['name']);
		}
		
		return $menu_data;
	}
			
	public function getMenuLinks($menu_id) {
		$menu_link_data = array();
		
		$menu_link_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_link WHERE menu_id = '" . (int)$menu_id . "'");
		
		foreach ($menu_link_query->rows as $menu_link) {
			$menu_link_description_data = array();
			 
			$menu_link_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_link_description WHERE menu_link_id = '" . (int)$menu_link['menu_link_id'] . "' AND menu_id = '" . (int)$menu_id . "'");
			
			foreach ($menu_link_description_query->rows as $menu_link_description) {			
				$menu_link_description_data[$menu_link_description['language_id']] = array('name' => $menu_link_description['name']);
			}
		
			$menu_link_data[] = array(
				'menu_link_description' => $menu_link_description_data,
				'heading'               => $menu_link['heading'],
				'link'                  => $menu_link['link'],
				'sort_order'            => $menu_link['sort_order']	
			);
		}
		
		return $menu_link_data;
	}
		
	public function getTotalMenus() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "menu");
		
		return $query->row['total'];
	}	
}
?>