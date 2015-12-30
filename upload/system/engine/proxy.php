<?php
class Proxy {
	protected $data = array();
	
	public function attach($method, $value) {
		$this->data[$method] = $value;
	}
		
	public function __call($method, $args) {
		if (isset($this->data[$method])) {
			return call_user_func($this->data[$method], $args);	
		} else {
			$trace = debug_backtrace();

			throw new \Exception('Error in: <b>' . $trace[1]['file'] . '</b> line <b>' . $trace[1]['line'] . '</b><br />' . $sql);
		}
	}
}