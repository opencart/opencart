<?php
namespace Cache;
class Mem {
	private $expire;
	private $cache;

	public function __construct($expire) {
		$this->expire = $expire;

		$this->cache = new \Memcache();
		$this->cache->pconnect(CACHE_HOSTNAME, CACHE_PORT);
	}

	public function get($key) {
		return $this->cache->get(CACHE_PREFIX . $key);
	}

	public function set($key, $value) {
		return $this->cache->set(CACHE_PREFIX . $key, $value, MEMCACHE_COMPRESSED, $this->expire);
	}

	public function delete($key) {
		$all_slabs = $this->memcache->getExtendedStats('slabs');
		foreach ($all_slabs as $server => $slabs) {
			foreach ($slabs as $slab_id => $slab_meta) {
				if (!is_int($slab_id)) {
					continue;
				}
				$cachedump = $this->memcache->getExtendedStats('cachedump', $slab_id, 1000000);
				foreach($cachedump as $server => $entries) {
					if (!empty($entries) && is_array($entries)) {
						foreach(array_keys($entries) as $entry_key) {
							if (strpos($entry_key, CACHE_PREFIX . $key) === 0) {
								$this->cache->delete($entry_key);
							}
						}
					}
				}
			}
		}
	}
}
