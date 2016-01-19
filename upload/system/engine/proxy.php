<?php
class Proxy {
	public function __get($key) {
		return $this->{$key};
	}	
	
	public function __set($key, $value) {
		 $this->{$key} = $value;
	}
	
	public function __call($key, $args) {
		return call_user_func($this->{$key}, $args);	
	}
}