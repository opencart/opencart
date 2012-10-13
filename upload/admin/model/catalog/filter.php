<?php
class ModelCatalogFilter extends Model {
	public function addFilter($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "filter` SET sort_order = '" . (int)$data['sort_order'] . "'");
		
		$filter_id = $this->db->getLastId();
		
		foreach ($data['filter_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}

		if (isset($data['filter_value'])) {
			foreach ($data['filter_value'] as $filter_value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "filter_value SET filter_id = '" . (int)$filter_id . "', sort_order = '" . (int)$filter_value['sort_order'] . "'");
				
				$filter_value_id = $this->db->getLastId();
				
				foreach ($filter_value['filter_value_description'] as $language_id => $filter_value_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "filter_value_description SET filter_value_id = '" . (int)$filter_value_id . "', language_id = '" . (int)$language_id . "', filter_id = '" . (int)$filter_id . "', name = '" . $this->db->escape($filter_value_description['name']) . "'");
				}
			}
		}
	}
	
	public function editFilter($filter_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "filter` SET sort_order = '" . (int)$data['sort_order'] . "' WHERE filter_id = '" . (int)$filter_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "filter_description WHERE filter_id = '" . (int)$filter_id . "'");

		foreach ($data['filter_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
				
		$this->db->query("DELETE FROM " . DB_PREFIX . "filter_value WHERE filter_id = '" . (int)$filter_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "filter_value_description WHERE filter_id = '" . (int)$filter_id . "'");
		
		if (isset($data['filter_value'])) {
			foreach ($data['filter_value'] as $filter_value) {
				if ($filter_value['filter_value_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "filter_value SET filter_value_id = '" . (int)$filter_value['filter_value_id'] . "', filter_id = '" . (int)$filter_id . "', sort_order = '" . (int)$filter_value['sort_order'] . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "filter_value SET filter_id = '" . (int)$filter_id . "', sort_order = '" . (int)$filter_value['sort_order'] . "'");
				}
				
				$filter_value_id = $this->db->getLastId();
				
				foreach ($filter_value['filter_value_description'] as $language_id => $filter_value_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "filter_value_description SET filter_value_id = '" . (int)$filter_value_id . "', language_id = '" . (int)$language_id . "', filter_id = '" . (int)$filter_id . "', name = '" . $this->db->escape($filter_value_description['name']) . "'");
				}
			}
		}
	}
	
	public function deleteFilter($filter_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter` WHERE filter_id = '" . (int)$filter_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_description` WHERE filter_id = '" . (int)$filter_id . "'");	
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_value` WHERE filter_id = '" . (int)$filter_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_value_description` WHERE filter_id = '" . (int)$filter_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_filter` WHERE filter_id = '" . (int)$filter_id . "'");
	}
	
	public function getFilter($filter_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter` f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_id = '" . (int)$filter_id . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
		
	public function getFilters($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "filter` f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE fd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$sql .= " AND LCASE(fd.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}

		$sort_data = array(
			'fd.name',
			'f.sort_order'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY fd.name";	
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
	
	public function getFilterDescriptions($filter_id) {
		$filter_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_description WHERE filter_id = '" . (int)$filter_id . "'");
				
		foreach ($query->rows as $result) {
			$filter_data[$result['language_id']] = array('name' => $result['name']);
		}
		
		return $filter_data;
	}
	
	public function getFilterValues($filter_id) {
		$filter_value_data = array();
		
		$filter_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_value fv LEFT JOIN " . DB_PREFIX . "filter_value_description fvd ON (fv.filter_value_id = fvd.filter_value_id) WHERE fv.filter_id = '" . (int)$filter_id . "' AND fvd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY fv.sort_order ASC");
				
		foreach ($filter_value_query->rows as $filter_value) {
			$filter_value_data[] = array(
				'filter_value_id' => $filter_value['filter_value_id'],
				'name'            => $filter_value['name'],
				'sort_order'      => $filter_value['sort_order']
			);
		}
		
		return $filter_value_data;
	}
	
	public function getFilterValueDescriptions($filter_id) {
		$filter_value_data = array();
		
		$filter_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_value WHERE filter_id = '" . (int)$filter_id . "'");
				
		foreach ($filter_value_query->rows as $filter_value) {
			$filter_value_description_data = array();
			
			$filter_value_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_value_description WHERE filter_value_id = '" . (int)$filter_value['filter_value_id'] . "'");			
			
			foreach ($filter_value_description_query->rows as $filter_value_description) {
				$filter_value_description_data[$filter_value_description['language_id']] = array('name' => $filter_value_description['name']);
			}
			
			$filter_value_data[] = array(
				'filter_value_id'          => $filter_value['filter_value_id'],
				'filter_value_description' => $filter_value_description_data,
				'sort_order'               => $filter_value['sort_order']
			);
		}
		
		return $filter_value_data;
	}

	public function getTotalFilters() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "filter`"); 
		
		return $query->row['total'];
	}		
}
?>