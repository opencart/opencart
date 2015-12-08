<?php
class Proxy {
	protected $registry;
	protected $data = array();
	
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function attach($method, $value) {
		$this->data[$method] = $value;
	}
		
	public function __call($method, $args) {
		if (array_key_exists($method, $this->data)) {
			return call_user_func($this->data[$method]->bindTo($this), $args);	
		} else {
			trigger_error('Error: Could not call ' . $method . '!');
			exit();	
		}
	}
}