<?php
/*
* Event System Userguide
* 
* https://github.com/opencart/opencart/wiki/Events-(script-notifications)-2.2.x.x
*/
class Event {
	protected $registry;
	protected $data = array();

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function register($trigger, Action $action) {
		$this->data[$trigger][] = $action;
	}
	
	public function unregister($trigger, Action $action = null) {
		if (!isset($this->data[$trigger])) {
			return;
		}

		if (null === $action) {
			unset($this->data[$trigger]);
		} else {
			foreach ($this->data[$trigger] as $data_key => $data_action) {
				if ($data_action->getRoute() == $action->getRoute()) {
					unset($this->data[$trigger][$data_key]);
				}
			}
		}
	}

	public function trigger($trigger, $args = array()) {
		foreach ($this->data as $key => $value) {
			if (preg_match('/^' . str_replace(array('\*', '\?'), array('.*', '.'), preg_quote($key, '/')) . '/', $trigger)) {
				foreach ($value as $event) {
					$result = $event->execute($this->registry, $args);
					
					if (!is_null($result) && !($result instanceof Exception)) {
						return $result;
					}
				}
			}
		}
	}
	
	public function getData() {
		return $this->data;
	}	
}
