<?php
class ModelLocalisationZone extends Model {
	public function getZonesByCountryId($country_id) {
		$zone = $this->cache->get('zone.' . $country_id);
	
		if (!$zone) {
			$query = $this->db->query("SELECT * FROM zone WHERE country_id = '" . (int)$country_id . "' ORDER BY name");
	
			$zone = $query->rows;
			
			$this->cache->set('zone.' . $country_id, $zone);
		}
	
		return $zone;
	}
}
?>