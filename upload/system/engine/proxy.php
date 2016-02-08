<?php
class Proxy {
	public function __get($key) {
		return $this->{$key};
	}	
	
	public function __set($key, $value) {
		 $this->{$key} = $value;
	}
	
	public function __call($key, $args) {
		if (isset($this->{$key})) {		
			return call_user_func($this->{$key}, $args);	
		} else {
			$trace = debug_backtrace();
			
			exit('<b>Notice</b>:  Undefined property: Proxy::' . $key . ' in <b>' . $trace[1]['file'] . '</b> on line <b>' . $trace[1]['line'] . '</b>');
		}
	}
}