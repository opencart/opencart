<?php
class ModelLocalisationLocation extends Model {
	public function getLocation($location_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "location WHERE location_id = '" . (int)$location_id . "'");

		return $query->row;
	}

	public function getLocations() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "location ORDER BY name ASC");

		return $query->rows;
	}
}