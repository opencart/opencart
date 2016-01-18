<?php
class ControllerStartupEvent extends Controller {
	public function index() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `trigger` LIKE 'admin/%'");
		
		foreach ($query->rows as $result) {
			$this->event->register(substr($result['trigger'], strrpos($result['trigger'], '/') + 1), new Action($result['action']));
		}
	}
}