<?php
/**
 * @package        OpenCart
 *
 * @author         Daniel Kerr
 * @copyright      Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license        https://opensource.org/licenses/GPL-3.0
 *
 * @see           https://www.opencart.com
 */
namespace Opencart\System\Library;
/**
 * Class Cache
 */
class Cache {
	/**
	 * @var object
	 */
	private object $adaptor;

	/**
	 * Constructor
	 *
	 * @param string $adaptor the type of storage for the cache
	 * @param int    $expire  Optional parameters
	 */
	public function __construct(string $adaptor, int $expire = 3600) {
		$class = 'Opencart\System\Library\Cache\\' . $adaptor;

		if (!class_exists($class)) {
			throw new \Exception('Error: Could not load cache adaptor ' . $adaptor . ' cache!');
		}

		$this->adaptor = new $class($expire);
	}

	/**
	 * Get
	 *
	 * Gets a cache by key name.
	 *
	 * @param string $key The cache key name
	 *
	 * @return mixed
	 */
	public function get(string $key) {
		return $this->adaptor->get($key);
	}

	/**
	 * Set
	 *
	 * Sets a cache by key value.
	 *
	 * @param string $key    The cache key
	 * @param mixed  $value  The cache value
	 * @param int    $expire The cache expiry
	 *
	 * @return void
	 */
	public function set(string $key, $value, int $expire = 0): void {
		$this->adaptor->set($key, $value, $expire);
	}

	/**
	 * Delete
	 *
	 * Deletes a cache by key name.
	 *
	 * @param string $key The cache key
	 *
	 * @return void
	 */
	public function delete(string $key): void {
		$this->adaptor->delete($key);
	}
}
