<?php
namespace Opencart\System\Library\Cache;
/**
 * Class Memcached
 *
 * @package
 */
class Memcached {
	/**
	 * @var object|\Memcached
	 */
	private object $memcached;
	/**
	 * @var int
	 */
	private int $expire;

	/**
	 *
	 */
	const CACHEDUMP_LIMIT = 9999;

	/**
	 * Constructor
	 *
	 * @param    int  $expire
	 */
	public function __construct(int $expire = 3600) {
		$this->expire = $expire;

		$this->memcached = new \Memcached();
		$this->memcached->addServer(CACHE_HOSTNAME, CACHE_PORT);
	}

	/**
	 * Get
	 *
	 * @param    string  $key
	 *
	 * @return	 array|string|null
	 */
	public function get(string $key): array|string|null {
		return $this->memcached->get(CACHE_PREFIX . $key);
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

		$this->memcached->set(CACHE_PREFIX . $key, $value, $expire);
	}

	/**
	 * Delete
	 *
	 * @param    string  $key
	 */
	public function delete(string $key) {
		$this->memcached->delete(CACHE_PREFIX . $key);
	}
}
