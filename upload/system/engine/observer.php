<?php
class Observer {
	private $object;

	public function __construct($object) {
		$this->object = $registry;
	}

	public function __call($method, $args = array()) {
		$this->object;
	}
}