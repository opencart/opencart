<?php
class DIContainer {
	public $registry;
	private $data = array();
	
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function attach($method, $function) {
		$this->data[$method] = $function;
	}		
	
	public function __call($method, $args = array()) {
		if (isset($args['reference'])) {
			$args = $args['reference'];
		}
		
		return call_user_func_array($this->data[$method]->bindTo($this), $args);
	}
}