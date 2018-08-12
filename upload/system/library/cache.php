<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Cache class
*/
class Cache {
	private $adaptor;
	
	/**
	 * Constructor
	 *
	 * @param	string	$adaptor	The type of storage for the cache.
	 * @param	int		$expire		Optional parameters
	 *
 	*/
	public function __construct($adaptor, $expire = 3600) {
		$class = 'Cache\\' . $adaptor;

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
	public function get($key) {
		return $this->adaptor->get($key);
	}
	
    /**
     * Sets a cache by key value.
     *
     * @param	string	$key	The cache key
	 * @param	string	$value	The cache value
	 * 
	 * @return	string
     */
	public function set($key, $value) {
		return $this->adaptor->set($key, $value);
	}
   
    /**
     * Deletes a cache by key name.
     *
     * @param	string	$key	The cache key
     */
	public function delete($key) {
		return $this->adaptor->delete($key);
	}
}
