<?php
class ModelLocalisationCountry extends Model {
	public function getCountry($country_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "'");
		
		return $query->row;
	}	
	
	public function getCountries() {
		$country_data = $this->cache->get('country');
		
		if (!$country_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country ORDER BY name ASC");
	
			$country_data = $query->rows;
		
			$this->cache->set('country', $country_data);
		}

		return $country_data;
	}
}
?>