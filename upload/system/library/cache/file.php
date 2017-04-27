<?php
namespace Cache;
class File {
	private $expire;

	public function __construct($expire = 3600) {
		$this->expire = $expire;

		$files1 = glob(DIR_CACHE . 'cache.*');
		$files2 = glob(DIR_CACHE . '*/cache.*');

		$files = array_merge($files1, $files2);

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

	public function get($key, $directory) {
		if (isset($directory)) {
			$cache_dir = rtrim(DIR_CACHE . str_replace(array('../', '..\\', '..'), '', $directory), '/');

			if (!is_dir($cache_dir)) {
				mkdir($cache_dir, 0777);
				chmod($cache_dir, 0777);
				@touch($cache_dir . '/' . 'index.html');
			}
		} else {
			$cache_dir = rtrim(DIR_CACHE, '/');
		}

		$files = glob($cache_dir . '/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

		if ($files) {
			$handle = fopen($files[0], 'r');

			flock($handle, LOCK_SH);

			$data = fread($handle, filesize($files[0]));

			flock($handle, LOCK_UN);

			fclose($handle);

			return json_decode($data, true);
		}

		return false;
	}

	public function set($key, $value, $directory) {
		if (isset($directory)) {
			$cache_dir = rtrim(DIR_CACHE . str_replace(array('../', '..\\', '..'), '', $directory), '/');

			if (!is_dir($cache_dir)) {
				mkdir($cache_dir, 0777);
				chmod($cache_dir, 0777);
				@touch($cache_dir . '/' . 'index.html');
			}
		} else {
			$cache_dir = rtrim(DIR_CACHE, '/');
		}

		$this->delete($key, $directory);

		$file = $cache_dir . '/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + $this->expire);

		$handle = fopen($file, 'w');

		flock($handle, LOCK_EX);

		fwrite($handle, json_encode($value));

		fflush($handle);

		flock($handle, LOCK_UN);

		fclose($handle);
	}

	public function delete($key, $directory) {
		if (isset($directory)) {
			$cache_dir = rtrim(DIR_CACHE . str_replace(array('../', '..\\', '..'), '', $directory), '/');
		} else {
			$cache_dir = rtrim(DIR_CACHE, '/');
		}

		$files = glob($cache_dir . '/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					unlink($file);
				}
			}
		}
	}
}