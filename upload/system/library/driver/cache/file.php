<?php
class CacheFile {
	private $expire;

	public function __construct($expire = 3600) {
		$this->expire = $expire;

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

	public function get($key) {
		$files = glob(DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

		if ($files) {			
			$file_handle = fopen($files[0], 'r');
			flock($file_handle, LOCK_SH);
			$data = fread($file_handle, filesize($files[0]));
			flock($file_handle, LOCK_UN);
			fclose($file_handle);
			
			return unserialize($data);
		}
		
		return false;
	}

	public function set($key, $value) {
		$file = DIR_CACHE . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + $this->expire);
		
		$fileHandle = fopen($file, 'w');
		flock($fileHandle, LOCK_EX);
		fwrite($fileHandle, serialize($value));
		fflush($fileHandle);
		flock($fileHandle, LOCK_UN);
		fclose($fileHandle);
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