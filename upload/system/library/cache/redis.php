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

		$host = defined('CACHE_HOSTNAME') ? CACHE_HOSTNAME : '127.0.0.1';
		$port = defined('CACHE_PORT') ? (int)CACHE_PORT : 6379;
		$password = defined('CACHE_PASSWORD') ? CACHE_PASSWORD : null;

		if (str_contains($host, 'unix:')) {
			$socketPath = preg_replace('#^unix:/*#', '/', $host);
			$this->redis->pconnect($socketPath, 0);
		} else {
			$this->redis->pconnect($host, $port);
		}

		if (!empty($password)) {
			$this->redis->auth($password);
		}
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

		if ($data === false) {
			return [];
		}

		$decoded = json_decode($data, true);

		return $decoded !== null ? $decoded : [];
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

	/**
	 * Clear
	 *
	 * Clear all cache
	 *
	 * @return void
	 */
	public function clear(): void {
		$this->redis->flushAll();
	}
}
