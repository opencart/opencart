<?php
class ModelSettingLocation extends Model {
    public function getLocation($location_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "location WHERE location_id = '" . (int)$location_id . "'");
        
        return $query->row; 
    }

    public function getLocations() {        
		$query = $this->db->query("SELECT l.location_id, l.name, l.address_1, l.address_2, l.city, l.postcode, c.name AS country, z.name AS zone, l.geocode, l.image, l.open, l.comment FROM " . DB_PREFIX . "location l LEFT JOIN " . DB_PREFIX . "country c ON (l.country_id = c.country_id) LEFT JOIN " . DB_PREFIX . "zone z ON (l.zone_id = z.zone_id)");

		return $query->rows;
    }
}
?>