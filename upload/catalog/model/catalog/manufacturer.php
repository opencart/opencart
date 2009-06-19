<?php
class ModelCatalogManufacturer extends Model {
	public function getManufacturer($manufacturer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
	
		return $query->row;	
	}
	
	public function getManufacturers() {
		$manufacturer = $this->cache->get('manufacturer');
		
		if (!$manufacturer) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer ORDER BY name");
	
			$manufacturer = $query->rows;
			
			$this->cache->set('manufacturer', $manufacturer);
		}
		 
		return $manufacturer;
	} 
}
?>