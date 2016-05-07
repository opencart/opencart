<?php
namespace Cache;
class APC {
	private $expire;
	private $active = false;

	public function __construct($expire) {
		$this->expire = $expire;
		if (function_exists('apc_cache_info')) {
			$this->active = true;
		}
	}

	public function get($key) {
		return $this->active ? apc_fetch(CACHE_PREFIX . $key) : false;
	}

	public function set($key, $value) {
		return $this->active ? apc_store(CACHE_PREFIX . $key, $value, $this->expire) : false;
	}

	public function delete($key) {
		if (!$this->active) {
			return false;
		}
		
		return apc_delete(CACHE_PREFIX . $key);
	}
}
