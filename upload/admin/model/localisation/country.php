<?php
class ModelLocalisationCountry extends Model {
	public function addCountry($data) {
		$this->db->query("INSERT INTO country SET name = '" . $this->db->escape($data['name']) . "', iso_code_2 = '" . $this->db->escape($data['iso_code_2']) . "', iso_code_3 = '" . $this->db->escape($data['iso_code_3']) . "', address_format = '" . $this->db->escape($data['address_format']) . "'");
	
		$this->cache->delete('country');
	}
	
	public function editCountry($country_id, $data) {
		$this->db->query("UPDATE country SET name = '" . $this->db->escape($data['name']) . "', iso_code_2 = '" . $this->db->escape($data['iso_code_2']) . "', iso_code_3 = '" . $this->db->escape($data['iso_code_3']) . "', address_format = '" . $this->db->escape($data['address_format']) . "' WHERE country_id = '" . (int)$country_id . "'");
	
		$this->cache->delete('country');
	}
	
	public function deleteCountry($country_id) {
		$this->db->query("DELETE FROM country WHERE country_id = '" . (int)$country_id . "'");
		
		$this->cache->delete('country');
	}
	
	public function getCountry($country_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM country WHERE country_id = '" . (int)$country_id . "'");
		
		return $query->row;
	}
		
	public function getCountries($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM country";
			
			if (isset($data['sort'])) {
				$sql .= " ORDER BY " . $this->db->escape($data['sort']);	
			} else {
				$sql .= " ORDER BY name";	
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
			$country = $this->cache->get('country');
		
			if (!$country) {
				$query = $this->db->query("SELECT * FROM country ORDER BY name ASC");
	
				$country = $query->rows;
			
				$this->cache->set('country', $country);
			}

			return $country;			
		}	
	}
	
	public function getTotalCountries() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM country");
		
		return $query->row['total'];
	}	
}
?>