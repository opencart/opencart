<?php
class Event {
	private $data = array();

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function register($key, $action) {
		$this->data[$key][] = $action;
	}

	public function unregister($key, $action) {
		unset($this->data[$key]);
	}

	public function trigger($key, &$arg = array()) {
		if (isset($this->data[$key])) {
			foreach ($this->data[$key] as $event) {
				$action = new Action($event, $arg);
				$action->execute($this->registry);
			}
		}
	}
}