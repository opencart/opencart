<?php
namespace Opencart\System\Library\Cache;
/**
 * Class Redis
 *
 * @package Opencart\System\Library\Cache
 */
class Redis {
	/**
	 * @var \Redis
	 */
	private \Redis $redis;
	/**
	 * @var int
	 */
	private int $expire;

	/**
	 * Constructor
	 *
	 * @param int $expire
	 */
	public function __construct(int $expire = 3600) {
		$this->expire = $expire;

		$this->redis = new \Redis();
		$this->redis->pconnect(CACHE_HOSTNAME, CACHE_PORT);
	}

	/**
	 * Get
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get(string $key) {
		$data = $this->redis->get(CACHE_PREFIX . $key);

		return json_decode($data, true);
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

		$status = $this->redis->set(CACHE_PREFIX . $key, json_encode($value));

		if ($status) {
			$this->redis->expire(CACHE_PREFIX . $key, $expire);
		}
	}

	/**
	 * Delete
	 *
	 * @param string $key
	 *
	 * @return void
	 */
	public function delete(string $key): void {
		$this->redis->del(CACHE_PREFIX . $key);
	}
}
