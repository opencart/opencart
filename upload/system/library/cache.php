<?php
class Cache { 
	private $cache;
	
	public function __construct($engine, $expire = 3600){
		$file = dirname(__FILE__) . '/engine/cache/' . $engine . '.php';
		
		if (file_exists($file)) {
			require_once($file);        
			
			$class = 'Cache'. $engine;
			
			$this->cache = new $class($expire);		
		} else {
			exit('Error: Could not load cache driver ' . $engine . ' cache!');
		}
	}
	
	public function get($key){
		return $this->cache->get($key);
	}
	
	public function set($key, $value) {
		return $this->cache->set($key,$value);                
	}
	
	public function delete($key) {
		return $this->cache->delete($key);
	}
}
