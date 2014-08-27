<?php
final class Action {
	private $class;
	private $method;
	private $args = array();

	public function __construct($route, $args = array()) {
		//preg_replace('/[^A-Z0-9\\\._-]/i', '', )
		$this->class = str_replace('/', '\\', $route);
		 
		// Stop any magical methods being called
		if (substr($this->method, 0, 2) == '__') {
			return false;
		}	
		
		$this->method = 'index';
	}

	public function getClass() {
		return $this->class;
	}
	
	public function getMethod() {
		return $this->method;
	}
	
	public function getArgs() {
		return $this->args;
	}
}