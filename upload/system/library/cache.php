<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/
namespace Opencart\System\Library;
/**
 * Class Cache
 */
class Cache {
	/**
	 * @var object|mixed
	 */
	private object $adaptor;
	
	/**
	 * Constructor
	 *
	 * @param	string	$adaptor	The type of storage for the cache.
	 * @param	int		$expire		Optional parameters
	 *
 	*/
	public function __construct(string $adaptor, int $expire = 3600) {
		$class = 'Opencart\System\Library\Cache\\' . $adaptor;

		if (class_exists($class)) {
			$this->adaptor = new $class($expire);
		} else {
			throw new \Exception('Error: Could not load cache adaptor ' . $adaptor . ' cache!');
		}
	}
	
    /**
     * Gets a cache by key name.
     *
     * @param	string $key	The cache key name
     *
     * @return	string
     */
	public function get(string $key): array|string|null {
		return $this->adaptor->get($key);
	}
	
    /**
	 * Set
	 *
     * Sets a cache by key value.
     *
     * @param	string	$key	The cache key
	 * @param	string	$value	The cache value
	 * 
	 * @return	string
     */
	public function set(string $key, array|string|null $value, int $expire = 0): void {
		$this->adaptor->set($key, $value, $expire);
	}
   
    /**
     * Deletes a cache by key name.
     *
     * @param	string	$key	The cache key
     */
	public function delete(string $key): void {
		$this->adaptor->delete($key);
	}
}
