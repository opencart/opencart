<?php
class Cache { 
	private $cache;
	public function __construct($expire=3600){
		if(defined('CACHE_DRIVER')){$driver=CACHE_DRIVER;}
		else{$driver="file";}
		if(file_exists(dirname(__FILE__) . "/driver/cache/". $driver. "cache" . ".php")){
			require_once(dirname(__FILE__) . "/driver/cache/". $driver. "cache" . ".php");	
		}else {
			exit("Error: Could not load cache driver ". $driver . "cache" . "!");
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
