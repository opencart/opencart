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
    /**
     * 
     *
     * @param	string	$key
     */	
	public function &__get($key) {
		return $this->{$key};
	}	

    /**
     * 
     *
     * @param	string	$key
	 * @param	string	$value
     */	
	public function __set($key, $value) {
		 $this->{$key} = $value;
	}
	
	public function __call($key, $args) {
		if (isset($this->{$key})) {
			return call_user_func_array($this->{$key}, $args);
		} else {
			$trace = debug_backtrace();

			exit('<b>Notice</b>:  Undefined property: Proxy::' . $key . ' in <b>' . $trace[1]['file'] . '</b> on line <b>' . $trace[1]['line'] . '</b>');
		}
	}
}