<?php
class ModelLocalisationZone extends Model {
	public function getZonesByCountryId($country_id) {
		$zone_data = $this->cache->get('zone.' . $country_id);
	
		if (!$zone_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' ORDER BY name");
	
			$zone_data = $query->rows;
			
			$this->cache->set('zone.' . $country_id, $zone_data);
		}
	
		return $zone_data;
	}
}
?>