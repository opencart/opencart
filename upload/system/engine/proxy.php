<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Proxy class
*/
class Proxy {
	private $data = array();

    /**
     * 
     *
     * @param	string	$key
     */	
	public function &__get($key) {
		return $this->data[$key];
	}

	public function attach($key, &$value) {
		$this->data[$key] = $value;
	}

    /**
     * 
     *
     * @param	string	$key
	 * @param	string	$value
     */	
	public function __set($key, $value) {
		$this->data[$key] = $value;
	}
	
	public function __call($method, $args) {
		// Hack for pass-by-reference
		foreach ($args as $key => &$value);

		if (isset($this->data[$method])) {
			return call_user_func_array($this->data[$method], $args);
		} else {
			$trace = debug_backtrace();

			exit('<b>Notice</b>:  Undefined property: Proxy::' . $key . ' in <b>' . $trace[1]['file'] . '</b> on line <b>' . $trace[1]['line'] . '</b>');
		}
	}
}