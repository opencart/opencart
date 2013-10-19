<?php
class Cache { 
	private $cache;
	public function __construct($expire=3600){
		if(defined('CACHE_DRIVER')){$driver=CACHE_DRIVER;}
		else{$driver="default";}
		if(file_exists(DIR_SYSTEM . "cache_driver/". $driver. ".php")){
			require_once(DIR_SYSTEM . "cache_driver/". $driver . ".php");	
		}else {
			exit("Error: Could not load cache driver ". $driver . "!");
		}
		$class="Cache".$driver;
		$this->cache=new $class($expire);
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
?>
