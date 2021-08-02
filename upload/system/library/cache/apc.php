<?php
namespace Opencart\System\Library\Cache;
class APC {
	private int $expire;
	private bool $active;

	public function __construct(int $expire = 3600) {
		$this->expire = $expire;
		$this->active = function_exists('apc_cache_info') && ini_get('apc.enabled');
	}

	public function get(string $key): array|string|null {
		return $this->active ? apc_fetch(CACHE_PREFIX . $key) : [];
	}

	public function set(string $key, array|string|null $value, int $expire = 0): void {
		if (!$expire) {
			$expire = $this->expire;
		}

		if ($this->active) {
			apc_store(CACHE_PREFIX . $key, $value, $expire);
		}
	}

	public function delete(string $key): void {
		if ($this->active) {
			$cache_info = apc_cache_info('user');

			$cache_list = $cache_info['cache_list'];

			foreach ($cache_list as $entry) {
				if (strpos($entry['info'], CACHE_PREFIX . $key) === 0) {
					apcu_delete($entry['info']);
				}
			}
		}
	}
}
