<?php
class ModelLocalisationLanguage extends Model {
	public function getLanguages() {
		$query = $this->db->query("SELECT * FROM language WHERE status = '1' ORDER BY name");
		
		return $query->rows;
	}
}
?>