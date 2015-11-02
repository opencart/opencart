<?php
class Event {
	protected $registry;
	private $data = array();

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function register($key, $action) {
		$this->data[$key][] = $action;
	}

	public function unregister($key, $action) {
		if (isset($this->data[$key])) {
			foreach ($this->data[$key] as $index => $event) {
				if ($event == $action) {
					unset($this->data[$key][$index]);
				}
			}
		}
	}

	public function trigger($key, &$arg = array()) {
		if (isset($this->data[$key])) {
			foreach ($this->data[$key] as $event) {
				if (!$arg) {
					$args = $event->getArgs();
				}
				
				$event->execute($this->registry, $arg);
			}
		}
	}
}