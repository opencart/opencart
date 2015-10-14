<?php
class Observer {
	private $data = array();

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function __call($method) {
		
	}

	public function register($key, $action, $priority = 0) {
		$this->data[$key][] = array(
			'action' => $action,
			'priority' => (int)$priority,
		);
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
				$action = $this->createAction($event['action'], $arg);
				$action->execute($this->registry);
			}
		}
	}
}