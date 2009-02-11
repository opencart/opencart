<?php
class ModelCatalogInformation extends Model {
	public function getInformation($information_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM information i LEFT JOIN information_description id ON (i.information_id = id.information_id) WHERE i.information_id = '" . (int)$information_id . "' AND id.language_id = '" . (int)$this->language->getId() . "'");
	
		return $query->row;
	}
	
	public function getInformations() {
		$query = $this->db->query("SELECT * FROM information i LEFT JOIN information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '" . (int)$this->language->getId() . "' ORDER BY i.sort_order ASC");
	
		return $query->rows;
	}
}
?>