<?php
class ModelLocalisationZone extends Model {
	public function getZonesByCountryId($country_id) {
		$query = $this->db->query("SELECT * FROM zone WHERE country_id = '" . (int)$country_id . "' ORDER BY name ASC");
		
		return $query->rows;
	}
}
?>