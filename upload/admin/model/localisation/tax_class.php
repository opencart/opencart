<?php 
class ModelLocalisationTaxClass extends Model {
	public function addTaxClass($data) {
		$this->db->query("INSERT INTO tax_class SET title = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "', date_added = NOW()");
		
		$tax_class_id = $this->db->getLastId();
		
		if (isset($data['tax_rate'])) {
			foreach ($data['tax_rate'] as $value) {
				$this->db->query("INSERT INTO tax_rate SET geo_zone_id = '" . (int)$value['geo_zone_id'] . "', tax_class_id = '" . (int)$tax_class_id . "', priority = '" . (int)$value['priority'] . "', rate = '" . (float)$value['rate'] . "', description = '" . $this->db->escape($value['description']) . "', date_added = NOW()");
			}
		}
		
		$this->cache->delete('tax_class');
	}
	
	public function editTaxClass($tax_class_id, $data) {
		$this->db->query("UPDATE tax_class SET title = '" . $this->db->escape($data['title']) . "', description = '" . $this->db->escape($data['description']) . "', date_modified = NOW() WHERE tax_class_id = '" . (int)$tax_class_id . "'");
		
		$this->db->query("DELETE FROM tax_rate WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		if (isset($data['tax_rate'])) {
			foreach ($data['tax_rate'] as $value) {
				$this->db->query("INSERT INTO tax_rate SET geo_zone_id = '" . (int)$value['geo_zone_id'] . "', tax_class_id = '" . (int)$tax_class_id . "', priority = '" . (int)$value['priority'] . "', rate = '" . (float)$value['rate'] . "', description = '" . $this->db->escape($value['description']) . "', date_added = NOW()");
			}
		}
		
		$this->cache->delete('tax_class');
	}
	
	public function deleteTaxClass($tax_class_id) {
		$this->db->query("DELETE FROM tax_class WHERE tax_class_id = '" . (int)$tax_class_id . "'");
		$this->db->query("DELETE FROM tax_rate WHERE tax_class_id = '" . (int)$tax_class_id . "'");
		
		$this->cache->delete('tax_class');
	}
	
	public function getTaxClass($tax_class_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM tax_class WHERE tax_class_id = '" . (int)$tax_class_id . "'");
		
		return $query->row;
	}

	public function getTaxClasses($data = array()) {
    	if ($data) {
			$sql = "SELECT * FROM tax_class";

			$sql .= " ORDER BY title";	
			
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
			$tax_class = $this->cache->get('tax_class');

			if (!$tax_class) {
				$query = $this->db->query("SELECT * FROM tax_class");
	
				$tax_class = $query->rows;
			
				$this->cache->set('tax_class', $tax_class);
			}
			
			return $tax_class;			
		}
	}
	
	public function getTaxRates($tax_class_id) {
      	$query = $this->db->query("SELECT * FROM tax_rate WHERE tax_class_id = '" . (int)$tax_class_id . "'");
		
		return $query->rows;
	}
			
	public function getTotalTaxClasses() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM tax_class");
		
		return $query->row['total'];
	}	
	
	public function getTotalTaxRatesByGeoZoneId($geo_zone_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM tax_rate WHERE geo_zone_id = '" . (int)$geo_zone_id . "'");
		
		return $query->row['total'];
	}		
}
?>