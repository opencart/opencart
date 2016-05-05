<?php
class ControllerStartupEvent extends Controller {
	public function index() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `trigger` LIKE 'admin/%' AND status = '1' ORDER BY `event_id` ASC");
		
		foreach ($query->rows as $result) {
			$this->event->register(substr($result['trigger'], strpos($result['trigger'], '/') + 1), new Action($result['action']));
		}
		
		// Add events from the DB
		$this->load->model('extension/event');
		
		$results = $this->model_extension_event->getEvents();
		
		foreach ($results as $result) {
			$this->event->register(substr($result['trigger'], strpos($result['trigger'], '/') + 1), new Action($result['action']));
		}		
	}
}