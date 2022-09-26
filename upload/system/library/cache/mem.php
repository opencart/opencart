<?php
namespace Opencart\System\Library\Cache;
class Mem {
	private int $expire;
	private object $memcache;

	const CACHEDUMP_LIMIT = 9999;

	/**
	 * Constructor
	 *
	 * @param    int  $expire
	 */
	public function __construct(int $expire = 3600) {
		$this->expire = $expire;

		$this->memcache = new \Memcache();
		$this->memcache->pconnect(CACHE_HOSTNAME, CACHE_PORT);
	}

	/**
	 * Get
	 *
	 * @param    string  $key
	 *
	 * @return	 array|string|null
	 */
	public function get(string $key): array|string|null {
		return $this->memcache->get(CACHE_PREFIX . $key);
	}

	/**
	 * Set
	 *
	 * @param    string  $key
	 * @param    array|string|null  $value
	 * @param	 int  $expire
	 */
	public function set(string $key, array|string|null $value, int $expire = 0) {
		if (!$expire) {
			$expire = $this->expire;
		}

		$this->memcache->set(CACHE_PREFIX . $key, $value, MEMCACHE_COMPRESSED, $expire);
	}
	
	/**
	 * Delete
	 *
	 * @param    string  $key
	 */
	public function delete(string $key) {
		$this->memcache->delete(CACHE_PREFIX . $key);
	}
}
