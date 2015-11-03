<?php
class Event {
	protected $registry;
	private $data = array();

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function register($trigger, $action) {
		$this->data[$trigger][] = $action;
	}

	public function unregister($trigger, $action) {
		if (isset($this->data[$trigger])) {
			foreach ($this->data[$trigger] as $key => $event) {
				if ($event == $action) {
					unset($this->data[$trigger][$key]);
				}
			}
		}
	}

	public function trigger($trigger, &$arg = array()) {
		if (isset($this->data[$trigger])) {
			foreach ($this->data[$trigger] as $event) {
				if (!$arg) {
					$args = $event->getArgs();
				}
				
				$event->execute($this->registry, $arg);
			}
		}
	}
}