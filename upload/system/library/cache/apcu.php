<?php
namespace Opencart\System\Library\Cache;
/**
 * Class APCU
 *
 * @package Opencart\System\Library\Cache
 */
class Apcu {
	/**
	 * @var int
	 */
	private int $expire;
	/**
	 * @var bool
	 */
	private bool $active;

	/**
	 * Constructor
	 *
	 * @param int $expire
	 */
	public function __construct(int $expire = 3600) {
		$this->expire = $expire;
		$this->active = function_exists('apcu_cache_info') && ini_get('apc.enabled');
	}

	/**
	 * Get
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get(string $key) {
		return $this->active ? apcu_fetch(CACHE_PREFIX . $key) : [];
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

		if ($this->active) {
			apcu_store(CACHE_PREFIX . $key, $value, $expire);
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
		if ($this->active) {
			$cache_info = apcu_cache_info();

			$cache_list = $cache_info['cache_list'];

			foreach ($cache_list as $entry) {
				if (strpos($entry['info'], CACHE_PREFIX . $key) === 0) {
					apcu_delete($entry['info']);
				}
			}
		}
	}

	/**
	 * Delete all cache
	 *
	 * @return bool
	 */
	public function flush(): bool {
		$status = false;

		if (function_exists('apcu_clear_cache')) {
			$status = apcu_clear_cache();
		}

		return $status;
	}
}
