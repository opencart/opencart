<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Registry class
*/
namespace Opencart\System\Engine;
class Registry {
	private array $data = [];

	/**
	 * __get
	 *
	 * https://www.php.net/manual/en/language.oop5.overloading.php#object.get
	 *
	 * @param    string $key
	 *
	 * @return   object
	 */
	public function __get(string $key): object|null {
		return $this->get($key);
	}

	/**
	 * __set
	 *
	 * https://www.php.net/manual/en/language.oop5.overloading.php#object.set
	 *
	 * @param    string $key
	 * @param    object $value
	 *
	 * @return    object
	 */
	public function __set(string $key, object $value): void {
		$this->set($key, $value);
	}
	/**
     * Get
     *
     * @param	string	$key
	 * 
	 * @return	mixed
     */
	public function get(string $key): object|null {
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}

    /**
     * Set
     *
     * @param	string	$key
	 * @param	string	$value
     */	
	public function set(string $key, object $value): void {
		$this->data[$key] = $value;
	}
	
    /**
     * Has
     *
     * @param	string	$key
	 *
	 * @return	bool
     */
	public function has(string $key): bool {
		return isset($this->data[$key]);
	}

	/**
	 * Unset
	 *
	 * Unsets registry value by key.
	 *
	 * @param	string	$key
	 *
	 * @return	void
	 */
	public function unset(string $key): void {
		if (isset($this->data[$key])) {
			unset($this->data[$key]);
		}
	}
}
