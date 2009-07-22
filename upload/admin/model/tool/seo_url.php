<?php
class ModelUrlAlias extends Model {
	public function addUrlAlias($query, $keyword) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id. "'");
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id. "', keyword = '" . $this->db->escape($data['keyword']) . "'");
	}
	
	public function deleteUrlAlias($query) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id. "'");
	}	
}
?>