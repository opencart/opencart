<?php
namespace Opencart\System\Library\Cache;
/**
 * Class Memcached
 *
 * @package Opencart\System\Library\Cache
 */
class Memcached {
	/**
	 * @var \Memcached
	 */
	private \Memcached $memcached;
	/**
	 * @var int
	 */
	private int $expire;

	public const CACHEDUMP_LIMIT = 9999;

	/**
	 * Constructor
	 *
	 * @param int $expire
	 */
	public function __construct(int $expire = 3600) {
		$this->expire = $expire;

		$this->memcached = new \Memcached();
		$this->memcached->addServer(CACHE_HOSTNAME, CACHE_PORT);
	}

	/**
	 * Get
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get(string $key) {
		return $this->memcached->get(CACHE_PREFIX . $key);
	}

	/**
	 * Set
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @param int    $expire
	 *
	 * @return void
	 */
	public function set(string $key, $value, int $expire = 0): void {
		if (!$expire) {
			$expire = $this->expire;
		}

		$this->memcached->set(CACHE_PREFIX . $key, $value, $expire);
	}

	/**
	 * Delete
	 *
	 * @param string $key
	 *
	 * @return void
	 */
	public function delete(string $key): void {
		$this->memcached->delete(CACHE_PREFIX . $key);
	}
}
