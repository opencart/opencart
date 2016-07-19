<?php
class ModelDesignTheme extends Model {
	public function editTheme($store_id, $theme, $route, $code) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "theme` WHERE store_id = '" . (int)$store_id . "' AND theme = '" . $this->db->escape($theme) . "' AND route = '" . $this->db->escape($route) . "'");
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "theme` SET store_id = '" . (int)$store_id . "', theme = '" . $this->db->escape($theme) . "', route = '" . $this->db->escape($route) . "', code = '" . $this->db->escape($code) . "', date_added = NOW()");
	}

	public function deleteTheme($theme_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "theme` WHERE theme_id = '" . (int)$theme_id . "'");
	}

	public function getTheme($store_id, $theme, $route) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "theme` WHERE store_id = '" . (int)$store_id . "' AND theme = '" . $this->db->escape($theme) . "' AND route = '" . $this->db->escape($route) . "'");

		return $query->row;
	}
	
	public function getThemes($start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}		
		
		$query = $this->db->query("SELECT *, (SELECT name FROM `" . DB_PREFIX . "store` s WHERE s.store_id = t.store_id) AS store FROM `" . DB_PREFIX . "theme` t ORDER BY t.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}	
	
	public function getTotalThemes() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "theme`");

		return $query->row['total'];
	}	
}