<?php
class ModelCatalogManufacturer extends Model {
	public function getManufacturer($manufacturer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m.manufacturer_id = '" . (int)$manufacturer_id . "' AND m2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
	
		return $query->row;	
	}
	
	public function getManufacturers() {
		$manufacturer = $this->cache->get('manufacturer.' . (int)$this->config->get('config_store_id'));
		
		if (!$manufacturer) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY sort_order, LCASE(m.name) ASC");
	
			$manufacturer = $query->rows;
			
			$this->cache->set('manufacturer.' . (int)$this->config->get('config_store_id'), $manufacturer);
		}
		
		return $manufacturer;
	} 
}
?>