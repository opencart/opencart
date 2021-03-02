<?php
namespace Opencart\System\Library\Cache;
class Redis {
	private int $expire;
	private object $cache;

	public function __construct(int $expire = 3600) {
		$this->expire = $expire;

		$this->cache = new \Redis();
		$this->cache->pconnect(CACHE_HOSTNAME, CACHE_PORT);
	}

	public function get(string $key): array|string|null {
		$data = $this->cache->get(CACHE_PREFIX . $key);

		return json_decode($data, true);
	}

	public function set(string $key, array|string|null $value, int $expire = 0) {
		if (!$expire) {
			$expire = $this->expire;
		}

		$status = $this->cache->set(CACHE_PREFIX . $key, json_encode($value));

		if ($status) {
			$this->cache->expire(CACHE_PREFIX . $key, $expire);
		}
	}

	public function delete(string $key): bool {
		$this->cache->del(CACHE_PREFIX . $key);
	}
}
