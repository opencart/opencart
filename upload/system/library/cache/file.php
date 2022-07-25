<?php
namespace Opencart\System\Library\Cache;
class File {
	private int $expire;

	public function __construct(int $expire = 3600) {
		$this->expire = $expire;

		$files = glob(DIR_CACHE . 'cache.*');

		if ($files) {
			foreach ($files as $file) {
				$filename = basename($file);

				$time = substr(strrchr($file, '.'), 1);

				if ($time < time()) {
					$this->delete(substr($filename, 6, strrpos($filename, '.') - 6));
				}
			}
		}
	}

	public function get(string $key): array|string|null {
		$files = glob(DIR_CACHE . 'cache.' . basename($key) . '.*');

		if ($files) {
			return json_decode(file_get_contents($files[0]), true);
		} else {
			return [];
		}
	}

	public function set(string $key, array|string|null $value, int $expire = 0): void {
		$this->delete($key);

		if (!$expire) {
			$expire = $this->expire;
		}

		file_put_contents(DIR_CACHE . 'cache.' . basename($key) . '.' . (time() + $expire), json_encode($value));
	}

	public function delete(string $key): void {
		$files = glob(DIR_CACHE . 'cache.' . basename($key) . '.*');

		if ($files) {
			foreach ($files as $file) {
				if (!@unlink($file)) {
					clearstatcache(false, $file);
				}
			}
		}
	}
}