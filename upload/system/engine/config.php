<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Config class
*/
namespace Opencart\System\Engine;
class Config {
	private $data = [];
    
	/**
     * 
     *
     * @param	string	$key
	 * 
	 * @return	mixed
     */
	public function get(string $key)  {
		return (isset($this->data[$key]) ? $this->data[$key] : '');
	}
	
    /**
     * 
     *
     * @param	string	$key
	 * @param	string	$value
     */
	public function set(string $key, $value) {
		$this->data[$key] = $value;
	}

    /**
     * 
     *
     * @param	string	$key
	 *
	 * @return	mixed
     */
	public function has(string $key) {
		return isset($this->data[$key]);
	}
	
    /**
     * 
     *
     * @param	string	$filename
     */
	public function load(string $filename) {
		$file = DIR_CONFIG . $filename . '.php';

		if (file_exists($file)) {
			$_ = [];

			require($file);

			$this->data = array_merge($this->data, $_);
		} else {
			trigger_error('Error: Could not load config ' . $filename . '!');
			exit();
		}
	}
}