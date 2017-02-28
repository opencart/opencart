<?php
namespace Cache;
class Memcached {
	private $expire;
	private $memcached;
	
	const CACHEDUMP_LIMIT = 9999;

	public function __construct($expire) {
		$this->expire = $expire;
		$this->memcached = new \Memcached();
		$this->memcached->addServer(CACHE_HOSTNAME, CACHE_PORT);
	}

	public function get($key) {
		return $this->memcached->get(CACHE_PREFIX . $key);
	}

	public function set($key, $value) {
		return $this->memcached->set(CACHE_PREFIX . $key, $value, $this->expire);
	}

	public function delete($key) {
		// Deletion from memcached server should have the same effect of the file cache adapter
		// More info https://github.com/opencart/opencart/commit/f62866a7710b5c7e8cc1155ffcc173a3ecd783bd
		$all_slabs = $this->memcache->getExtendedStats('slabs');
		foreach ($all_slabs as $server => $slabs) {
			foreach ($slabs as $slab_id => $slab_meta) {
				if (!is_int($slab_id)) {
					continue;
				}
				$cachedump = $this->memcache->getExtendedStats('cachedump', $slab_id, self::CACHEDUMP_LIMIT);
				foreach ($cachedump as $server => $entries) {
					if (!empty($entries) && is_array($entries)) {
						foreach (array_keys($entries) as $entry_key) {
							if (strpos($entry_key, CACHE_PREFIX . $key) === 0) {
								$this->memcache->delete($entry_key);
							}
						}
					}
				}
			}
		}
	}

	public function flush() {
		$this->memcached->flush();
	}
}
