<?php
namespace Opencart\System\Library\Cache;
class Memcached {
	private int $expire;
	private object $memcached;

	const CACHEDUMP_LIMIT = 9999;

	public function __construct(int $expire = 3600) {
		$this->expire = $expire;

		$this->memcached = new \Memcached();
		$this->memcached->addServer(CACHE_HOSTNAME, CACHE_PORT);
	}

	public function get(string $key): array|string|null {
		return $this->memcached->get(CACHE_PREFIX . $key);
	}

	public function set(string $key, array|string|null $value, int $expire = 0) {
		if (!$expire) {
			$expire = $this->expire;
		}

		$this->memcached->set(CACHE_PREFIX . $key, $value, $expire);
	}

	public function delete(string $key) {
		$this->memcached->delete(CACHE_PREFIX . $key);
	}
}
