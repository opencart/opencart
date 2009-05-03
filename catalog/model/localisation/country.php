<?php
class ModelLocalisationCountry extends Model {
	public function getCountries() {
		$country = $this->cache->get('country');
		
		if (!$country) {
			$query = $this->db->query("SELECT * FROM country ORDER BY name ASC");
	
			$country = $query->rows;
		
			$this->cache->set('country', $country);
		}

		return $country;
	}
	
	public function getCountry($country_id) {
		$query = $this->db->query("SELECT * FROM country WHERE country_id = '" . (int)$country_id . "'");
		
		return $query->row;
	}	
}
?>