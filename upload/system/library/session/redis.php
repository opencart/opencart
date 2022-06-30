<?php
namespace Opencart\System\Library\Session;
class Redis
{
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

	public function read(string $session_id): array	{
		$data = $this->redis->get($this->prefix . $session_id);
		if (is_null($data) || empty($data))
			return [];
		return json_decode($data, true);
	}

	public function write(string $session_id, array $data): bool {
		if ($session_id) {
			$this->redis->set($this->prefix . $session_id, $data ? json_encode($data) : '', $this->config->get('session_expire'));
		}

		return true;
	}

	public function destroy(string $session_id): bool {
		$this->redis->unlink($this->prefix . $session_id);

		return true;
	}

	public function gc(): bool {
		// Redis will take care of Garbage Collection itself.

		return true;
	}
}
