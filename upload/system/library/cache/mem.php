<?php
namespace Cache;
class Mem {
	private $expire;
	private $memcache;
	
	const CACHEDUMP_LIMIT = 9999;

	public function __construct($expire) {
		$this->expire = $expire;

		$this->memcache = new \Memcache();
		$this->memcache->pconnect(CACHE_HOSTNAME, CACHE_PORT);
	}

	public function get($key) {
		return $this->memcache->get(CACHE_PREFIX . $key);
	}

	public function set($key, $value) {
		return $this->memcache->set(CACHE_PREFIX . $key, $value, MEMCACHE_COMPRESSED, $this->expire);
	}

	public function delete($key) {
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
}
