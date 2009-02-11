<?php
final class Registry {
	static private $data = array();

	static public function get($key) {
		return (isset(self::$data[$key]) ? self::$data[$key] : NULL);
	}

	static public function set($key, $value) {
		self::$data[$key] = $value;
	}

	static public function has($key) {
    	return isset(self::$data[$key]);
  	}	
}
?>