<?php
class ModelSettingStore extends Model {
	public function getStore($store_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "store s LEFT JOIN " . DB_PREFIX . "store_description sd ON (s.store_id = sd.store_id) WHERE s.store_id = '" . (int)$store_id . "' AND sd.language_id = '" . $this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
	
	public function getStores($data = array()) {
		$store_data = $this->cache->get('store');
	
		if (!$store_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY url");

			$store_data = $query->rows;
		
			$this->cache->set('store', $store_data);
		}
	 
		return $store_data;
	}	
}
?>