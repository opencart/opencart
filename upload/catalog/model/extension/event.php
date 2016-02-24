<?php
class ModelExtensionEvent extends Model {
	function getEvents() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `trigger` LIKE 'catalog/%' ORDER BY `event_id` ASC");

		return $query->rows;
	}
}