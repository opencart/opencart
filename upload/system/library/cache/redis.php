<?php
namespace Cache;
class Redis {
	private $cache;

	public function __construct() {

		$scheme = 'tcp';
		$host = defined('CACHE_HOSTNAME') ? CACHE_HOSTNAME : '10.0.0.1';
		$port = defined('CACHE_PORT') ? CACHE_PORT : '6379';

		$this->cache = new \Predis\Client(array(
			'scheme' => $scheme,
			'host'   => $host,
			'port'   => $port,
		));
	}

	public function get($key) {
		return json_decode($this->cache->get($key), true);
	}

	public function set($key, $value) {
		return $this->cache->set($key, json_encode($value));
	}

	public function delete($key) {
		$this->cache->del(array($key));
	}

}