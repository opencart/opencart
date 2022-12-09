<?php
namespace Opencart\System\Library\Session;
class Redis {
	private object $config;
	private object $redis;
	/**
	 * Construct
	 *
	 * @param    object  $registry
	 */
	public function __construct(\Opencart\System\Engine\Registry $registry)	{
		$this->config = $registry->get('config');

		try {
			$this->redis = new \Redis();
			$this->redis->pconnect(CACHE_HOSTNAME, CACHE_PORT);
			$this->prefix = CACHE_PREFIX . '.session.'; // session prefix to identify session keys
		} catch (\RedisException $e) {
			//
		}
	}

	/**
	 * Read
	 *
	 * @param    string  $session_id
	 *
	 * @return	 array
	 */
	public function read(string $session_id): array	{
		$data = $this->redis->get($this->prefix . $session_id);
		if (is_null($data) || empty($data))
			return [];
		return json_decode($data, true);
	}

	/**
	 * Write
	 *
	 * @param    string  $session_id
	 * @param    array   $data
	 *
	 * @return	 bool
	 */
	public function write(string $session_id, array $data): bool {
		if ($session_id) {
			$this->redis->set($this->prefix . $session_id, $data ? json_encode($data) : '', $this->config->get('session_expire'));
		}

		return true;
	}
	
	/**
	 * Destroy
	 *
	 * @param    string  $session_id
	 *
	 * @return	 bool
	 */
	public function destroy(string $session_id): bool {
		$this->redis->unlink($this->prefix . $session_id);

		return true;
	}

	/**
	 * GC
	 *
	 * @return	 bool
	 */
	public function gc(): bool {
		// Redis will take care of Garbage Collection itself.

		return true;
	}
}
