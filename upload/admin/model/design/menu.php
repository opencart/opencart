<?php
class ModelDesignMenu extends Model {
	public function addMenu($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "menu SET store_id = '" . (int)$data['store_id'] . "', type = '" .  $this->db->escape($data['type']) . "', link = '" .  $this->db->escape($data['link']) . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "'");
	
		$menu_id = $this->db->getLastId();
	
		if (isset($data['menu_description'])) {
			foreach ($data['menu_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int)$menu_id . "', language_id = '" . (int)$language_id . "', name = '" .  $this->db->escape($value['name']) . "'");
			}
		}	
	}

	public function editMenu($menu_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "menu SET store_id = '" . (int)$data['store_id'] . "', type = '" .  $this->db->escape($data['type']) . "', link = '" .  $this->db->escape($data['link']) . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "' WHERE menu_id = '" . (int)$menu_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_description WHERE menu_id = '" . (int)$menu_id . "'");

		if (isset($data['menu_description'])) {
			foreach ($data['menu_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int)$menu_id . "', language_id = '" . (int)$language_id . "', name = '" .  $this->db->escape($value['name']) . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_module WHERE menu_id = '" . (int)$menu_id . "'");
		
		if (isset($data['menu_description'])) {
			foreach ($data['menu_description'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "menu_description SET menu_id = '" . (int)$menu_id . "', language_id = '" . (int)$language_id . "', name = '" .  $this->db->escape($value['name']) . "'");
			}
		}		
	}

	public function deleteMenu($menu_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu WHERE menu_id = '" . (int)$menu_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_description WHERE menu_id = '" . (int)$menu_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "menu_module WHERE menu_id = '" . (int)$menu_id . "'");
	}

	public function getMenu($menu_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "menu WHERE menu_id = '" . (int)$menu_id . "'");

		return $query->row;
	}

	public function getMenus($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "menu m LEFT JOIN " . DB_PREFIX . "menu_description md ON(m.menu_id = md.menu_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = array(
			'md.name',
			'm.store_id',
			'm.sort_order',
			'm.status'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY md.name";
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
		$menu_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_description WHERE menu_id = '" . (int)$menu_id . "'");

		foreach ($query->rows as $result) {
			$menu_description_data[$result['language_id']] = array('name' => $result['name']);
		}

		return $menu_description_data;
	}

	public function getTotalMenus() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "menu");

		return $query->row['total'];
	}
        
        public function getMenuModules($menu_id) {
                $menu_modules_data = array();

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "menu_module WHERE menu_id = '" . (int)$menu_id . "'");

                foreach ($query->rows as $result) {
                        $menu_modules_data[$result['menu_module_id']] = array('code' => $result['code'], 'sort_order' => $result['sort_order']);
                }

                return $menu_modules_data;
        }       
}
