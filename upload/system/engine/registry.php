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
	private $data = [];

	/**
     * Get
     *
     * @param	string	$key
	 * 
	 * @return	mixed
     */
	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : '');
	}

    /**
     * Set
     *
     * @param	string	$key
	 * @param	string	$value
     */	
	public function set($key, $value) {
		$this->data[$key] = $value;
	}
	
    /**
     * Has
     *
     * @param	string	$key
	 *
	 * @return	bool
     */
	public function has($key) {
		return isset($this->data[$key]);
	}
}