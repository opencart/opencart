<?php
class Event {
	private $events = array();
	private $load;

	public function __construct($registry) {
		$this->load = $registry->get('load');
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "event WHERE store_id = '0' OR store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY store_id ASC");

		foreach ($query->rows as $e) {
			$handlers = unserialize($e['handlers']);

			foreach ($handlers as $handler) {
				$this->register($e['event'], $handler);
			}
		}
	}

	public function register($event, $handler) {
		if (!array_key_exists($event, $this->events)) {
			$this->events[$event] = array();
		}

		if (is_string($handler)) {
			$this->events[$event][] = $handler;
		} else {
			return false;
		}

		return true;
	}

	public function unregister($event, $handler) {
		if (!array_key_exists($event, $this->data)) {
			return true;
		}

		if (in_array($handler, $this->data[$event])) {
			$key = array_search($handler, $this->data[$event]);
			
			unset($this->data[$event][$key]);
		}

		return true;
	}

	public function trigger($event, &$data = array()) {
		if (!array_key_exists($event, $this->events)) {
			return true;
		}

		foreach ($this->events[$event] as $handler) {
			$parts = explode('/', $handler);

			$event = $this->load->event($parts[0] . '/' . $parts[1]);

			if (is_callable(array($event, $parts[2]))) {
				$event->{$parts[2]}($data);
			}
		}

		return true;
	}
}