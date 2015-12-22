<?php
class ControllerActionEvent extends Controller {
	public function index() {
		// Add events from the DB
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `trigger` LIKE 'catalog/%'");
		
		foreach ($query->rows as $result) {
			$this->event->register(substr($result['trigger'], strpos($result['trigger'], '/') + 1), new Action($result['action']));
		}
	}
}