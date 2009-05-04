<?php
abstract class Model {
	public function __get($key) {
		return Registry::get($key);
	}
	
	public function __set($key, $value) {
		Registry::set($key, $value);
	}
}
?>