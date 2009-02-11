<?php
final class Cache { 
	private $expire = 3600;

  	public function __construct() {
		$files = glob(DIR_CACHE . 'cache.*');
    	
		foreach ($files as $file) {
      		$time = end(explode('.', basename($file)));

      		if ($time < time()) {
				unlink($file);
      		}
    	}	
  	}

	public function get($key) {
    	foreach (glob(DIR_CACHE . 'cache.' . $key . '.*') as $file) {
      		$handle = fopen($file, 'r');
      		$cache  = fread($handle, filesize($file));
	  
      		fclose($handle);

      		return unserialize($cache);
    	}
  	}

  	public function set($key, $value) {
    	$this->delete($key);
		
		$file = DIR_CACHE . 'cache.' . $key . '.' . (time() + $this->expire);
    	
		$handle = fopen($file, 'w');

    	fwrite($handle, serialize($value));
		
    	fclose($handle);
  	}
	
  	public function delete($key) {
    	foreach (glob(DIR_CACHE . 'cache.' . $key . '.*') as $file) {
      		unlink($file);
    	}
  	}
}
?>