<?php
class Event {
	private $data = array();
	private $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function register($key, $action) {
		$this->data[$key][] = $action;
	}

	public function unregister($key, $action) {
		if (isset($this->data[$key])) {
			foreach ($this->data[$key] as $index => $event) {
				if ($event['action'] == $action) {
					unset($this->data[$key][$index]);
				}
			}
		}
	}

	public function trigger($key, &$arg = array()) {
		if (isset($this->data[$key])) {
			usort($this->data[$key], array("Event", "cmpByPriority"));
			foreach ($this->data[$key] as $event) {
				$action = new Action($event['action'], $arg);
				$action->execute($this->registry);
			}
		}
	}
}