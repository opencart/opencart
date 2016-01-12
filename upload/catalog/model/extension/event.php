<?php
class ModelExtensionEvent extends Model {
	function getEvents() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `trigger` LIKE 'catalog/%'");

		return $query->rows;
	}
}