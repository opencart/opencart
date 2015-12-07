<?php
class Observer {
	protected $registry;
	protected $data = array();
	
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function attach($method, $value) {
		$this->data[$method] = $value;
	}
		
	public function __call($method, $args) {
		return call_user_func($this->data[$method]->bindTo($this), $args);	
	}
}