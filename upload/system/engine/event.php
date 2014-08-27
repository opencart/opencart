<?php
class Event {
	private $data = array();

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function register($event, $action) {
		$this->data[$event][] = $action;
	}

	public function unregister($event, $action) {
		unset($this->data[$event][$key]);
	}

	public function trigger($event, &$data = array()) {
		if (!array_key_exists($event, $this->events)) {
			return true;
		}

		foreach ($this->data[$event] as $action) {
			$action->execute($data);
		}

		return true;
	}
}