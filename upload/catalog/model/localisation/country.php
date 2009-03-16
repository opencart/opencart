<?php
class ModelLocalisationCountry extends Model {
	public function getCountries() {
		$query = $this->db->query("SELECT * FROM country ORDER BY name ASC");
		
		return $query->rows;
	}
}
?>