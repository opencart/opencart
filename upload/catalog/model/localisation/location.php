<?php
class ModelLocalisationLocation extends Model {
    public function getLocation($location_id) {
        $query = $this->db->query("SELECT l.location_id, l.name, l.telephone, l.fax, l.address_1, l.address_2, l.city, l.postcode, c.name AS country, c.address_format, z.name AS zone, z.code as zone_code, l.geocode, l.image, l.open, l.comment FROM " . DB_PREFIX . "location l LEFT JOIN " . DB_PREFIX . "country c ON (l.country_id = c.country_id) LEFT JOIN " . DB_PREFIX . "zone z ON (l.zone_id = z.zone_id) WHERE l.location_id = '" . (int)$location_id . "'");
        
        return $query->row; 
    }

    public function getLocations() {        
		$query = $this->db->query("SELECT l.location_id, l.name, l.telephone, l.fax, l.address_1, l.address_2, l.city, l.postcode, c.name AS country, c.address_format, z.name AS zone, z.code as zone_code, l.geocode, l.image, l.open, l.comment FROM " . DB_PREFIX . "location l LEFT JOIN " . DB_PREFIX . "country c ON (l.country_id = c.country_id) LEFT JOIN " . DB_PREFIX . "zone z ON (l.zone_id = z.zone_id) ORDER BY l.name");

		return $query->rows;
    }
}
?>