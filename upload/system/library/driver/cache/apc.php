<?php
<<<<<<< HEAD:upload/system/library/driver/cache/apc.php
class CacheAPC {
=======
class Cacheapc {
>>>>>>> 277eab7e073568beba1ab82ad8299729b1631544:upload/system/library/driver/cache/apccache.php
	private $expire;
	private $cache;
	
	public function __construct($expire) {
		$this->expire = $expire;                
	}
	
	public function get($key) {
		return apc_fetch(CACHE_PREFIX . $key);
	}
	
	public function set($key, $value) {
		return apc_store(CACHE_PREFIX . $key, $value, $this->expire);        
	}
	
	public function delete($key) {
		apc_delete(CACHE_PREFIX . $key);        
	}
}
