<?php
class ModelCatalogFilter extends Model {
	public function getFilterGroupsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_group_to_category fg2c LEFT JOIN " . DB_PREFIX . "filter_group fg ON (fg2c.filter_group_id = fg.filter_group_id) LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE fg2c.category_id = '" . (int)$category_id . "' AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY fg.sort_order");
		
		return $query->rows;
	}
	
	public function getFiltersByFilterGroupId($filter_group_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_group_id = '" . (int)$filter_group_id . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY f.sort_order");

		return $query->rows;
	}
}
?>