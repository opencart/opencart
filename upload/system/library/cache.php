<?php
class Cache { 
	private $cache;
	
	public function __construct($driver, $expire = 3600){
		$file = dirname(__FILE__) . '/driver/cache/' . $driver . '.php';
		
		if (file_exists($file)) {
			require_once($file);        
			
			$class = 'Cache'. $driver;
			
			$this->cache = new $class($expire);		
		} else {
			exit('Error: Could not load cache driver ' . $driver . ' cache!');
		}
	}
	
	public function get($key){
		return $this->cache->get($key);
	}
	
	public function set($key, $value) {
		return $this->cache->set($key, $value);
	}
	
	public function delete($key) {
		return $this->cache->delete($key);
	}
}
