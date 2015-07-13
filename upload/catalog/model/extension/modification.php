<?php
class ModelExtensionModification extends Model {
	public function getModifications() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "modification ORDER BY name ASC");

		return $query->rows;
	}
}