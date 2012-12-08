<?php
class ModelCatalogFilter extends Model {
	public function getFilterGroupsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_group_to_category fg2c LEFT JOIN " . DB_PREFIX . "filter f ON (fg2c.filter_group_id = f.filter_id) LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE fg2c.category_id = '" . (int)$category_id . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY f.sort_order");
		
		return $query->row;
	}
	
	public function getFilters($filter_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_id = '" . (int)$filter_id . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY f.sort_order");

		return $query->rows;
	}
}
?>