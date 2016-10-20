<?php
class ModelDesignTheme extends Model {
	public function editTheme($store_id, $theme, $route, $code) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "theme` WHERE store_id = '" . (int)$store_id . "' AND theme = '" . $this->db->escape($theme) . "' AND route = '" . $this->db->escape($route) . "'");
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "theme` SET store_id = '" . (int)$store_id . "', theme = '" . $this->db->escape($theme) . "', route = '" . $this->db->escape($route) . "', code = '" . $this->db->escape($code) . "'");
	}

	public function deleteTheme($store_id, $theme, $route) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "theme` WHERE store_id = '" . (int)$store_id . "' AND theme = '" . $this->db->escape($theme) . "' AND route = '" . $this->db->escape($route) . "'");
	}

	public function getTheme($store_id, $theme, $route) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "theme` WHERE store_id = '" . (int)$store_id . "' AND theme = '" . $this->db->escape($theme) . "' AND route = '" . $this->db->escape($route) . "'");

		return $query->row;
	}
}