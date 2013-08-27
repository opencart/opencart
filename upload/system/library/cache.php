<?php
class Cache { 
	private $expire; 
	
	public function __construct($registry) {

		$this->config = $registry->get('config');
		$this->expire = $this->config->get('config_cache_expire');

		$files = glob(DIR_CACHE . 'cache.*');

		if ($files) {			
			foreach ($files as $file) {
				$time = substr(strrchr($file, '.'), 1);

				if ($time < time()) {
					if (file_exists($file)) {
						unlink($file);
					}
				}
			}
		}
	}

	public function getExpire(){

		return $this->expire;
	}
	

	public function setExpire($cache_expire){

		$this->expire = $cache_expire;
	}

	public function get($key) {
		$files = glob(DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

		if ($files) {
			$handle = fopen($files[0], 'r');
      		
			$cache = fread($handle, filesize($files[0]));
			
			fclose($handle);
		
			return unserialize($cache);
		}
	}

	public function set($key, $value) {
		$this->delete($key);

		$file = DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + $this->expire);

		$handle = fopen($file, 'w');

		fwrite($handle, serialize($value));

		fclose($handle);
	}

	public function delete($key) {
		$files = glob(DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					unlink($file);
				}
			}
		}
	}
}
?>
