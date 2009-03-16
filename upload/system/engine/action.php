<?php
final class Action {
	protected $class;
	protected $method;
	protected $args = array();
	
	public function __construct($class, $method, $args = array()) {
		$this->class = $class;
		$this->method = $method;
		$this->args = $args;
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
?>