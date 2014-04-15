<?php
class CacheFile { 
	private $expire; 
	
	public function __construct($expire = 3600) {
		$this->expire = $expire;
		
		if (rand(1,100) <= 30 && $files = glob(DIR_CACHE . 'cache.*')) {
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

		file_put_contents($file, serialize($value), LOCK_EX);
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
