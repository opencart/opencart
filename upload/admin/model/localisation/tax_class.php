<?php 
class ModelLocalisationTaxClass extends Model {
	public function addTaxClass($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "tax_class SET title = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "', date_added = NOW()");
		
		$tax_class_id = $this->db->getLastId();
		
		if (isset($data['tax_rate'])) {
			foreach ($data['tax_rate'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "tax_rate SET geo_zone_id = '" . (int)$value['geo_zone_id'] . "', tax_class_id = '" . (int)$tax_class_id . "', priority = '" . (int)$value['priority'] . "', rate = '" . (float)$value['rate'] . "', description = '" . $this->db->escape($value['description']) . "', date_added = NOW()");
			}
		}
		
		$this->cache->delete('tax_class');
	}
	
	public function editTaxClass($tax_class_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "tax_class SET title = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "', date_modified = NOW() WHERE tax_class_id = '" . (int)$tax_class_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "tax_rate WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		if (isset($data['tax_rate'])) {
			foreach ($data['tax_rate'] as $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "tax_rate SET geo_zone_id = '" . (int)$value['geo_zone_id'] . "', tax_class_id = '" . (int)$tax_class_id . "', priority = '" . (int)$value['priority'] . "', rate = '" . (float)$value['rate'] . "', description = '" . $this->db->escape($value['description']) . "', date_added = NOW()");
			}
		}
		
		$this->cache->delete('tax_class');
	}
	
	public function deleteTaxClass($tax_class_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "tax_class WHERE tax_class_id = '" . (int)$tax_class_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "tax_rate WHERE tax_class_id = '" . (int)$tax_class_id . "'");
		
		$this->cache->delete('tax_class');
	}
	
	public function getTaxClass($tax_class_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_class WHERE tax_class_id = '" . (int)$tax_class_id . "'");
		
		return $query->row;
	}

	public function getTaxClasses($data = array()) {
    	if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "tax_class";

			$sql .= " ORDER BY title";	
			
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
		} else {
			$tax_class_data = $this->cache->get('tax_class');

			if (!$tax_class_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_class");
	
				$tax_class_data = $query->rows;
			
				$this->cache->set('tax_class', $tax_class_data);
			}
			
			return $tax_class_data;			
		}
	}
	
	public function getTaxRates($tax_class_id) {
      	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_rate WHERE tax_class_id = '" . (int)$tax_class_id . "'");
		
		return $query->rows;
	}
			
	public function getTotalTaxClasses() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tax_class");
		
		return $query->row['total'];
	}	
	
	public function getTotalTaxRatesByGeoZoneId($geo_zone_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tax_rate WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");
		
		return $query->row['total'];
	}		
}
?>