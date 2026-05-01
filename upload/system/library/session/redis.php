<?php
namespace Opencart\System\Library\Session;
/**
 * Class Redis
 *
 * @package Opencart\System\Library\Session
 */
class Redis {
	/**
	 * @var object
	 */
	private object $config;
	/**
	 * @var \Redis
	 */
	private \Redis $redis;
	/**
	 * @var string
	 */
	private string $prefix = '';

	/**
	 * Constructor
	 *
	 * @param \Opencart\System\Engine\Registry $registry
	 */
	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->config = $registry->get('config');

		try {
			$this->redis = new \Redis();
			$this->redis->pconnect(CACHE_HOSTNAME, CACHE_PORT);
			$this->prefix = CACHE_PREFIX . '.session.';
		} catch (\RedisException $e) {
			throw new \Exception('Error: Could not connect to Redis session backend! Message: ' . $e->getMessage());
		}
	}

	/**
	 * Read
	 *
	 * @param string $session_id
	 *
	 * @return array<mixed>
	 */
	public function read(string $session_id): array {
		$data = $this->redis->get($this->prefix . $session_id);

		if (!$data) {
			return [];
		}

		$decoded = json_decode($data, true);

		return is_array($decoded) ? $decoded : [];
	}

	/**
	 * Write
	 *
	 * @param string       $session_id
	 * @param array<mixed> $data
	 *
	 * @return bool
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
	 * @param string $session_id
	 *
	 * @return bool
	 */
	public function destroy(string $session_id): bool {
		$this->redis->unlink($this->prefix . $session_id);

		return true;
	}

	/**
	 * GC
	 *
	 * @return bool
	 */
	public function gc(): bool {
		// Redis will take care of Garbage Collection itself.

		return true;
	}
}
