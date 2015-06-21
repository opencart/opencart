<?php
class ModelLocalisationZone extends Model {
	public function getZone($zone_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE zone_id = '" . (int)$zone_id . "' AND status = '1'");

		return $query->row;
	}

	public function getZonesByCountryId($country_id) {
		$zone_data = $this->cache->get('zone.' . (int)$country_id);

		if (!$zone_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone WHERE country_id = '" . (int)$country_id . "' AND status = '1' ORDER BY name");

			$zone_data = $query->rows;

			if( count($zone_data) < 1){
				$zone_data = array(array('zone_id' => '0000', 'country_id' => $country_id, 'name' => 'No zone', 'code' => 'XX', 'status' => '1'));
			}
			
			$this->cache->set('zone.' . (int)$country_id, $zone_data);
		}

		return $zone_data;
	}
}