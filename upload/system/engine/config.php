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
	protected $directory;
	private $path = [];
	private $data = [];

	/**
	 * addPath
	 *
	 * @param    string $namespace
	 * @param    string $directory
	 */
	public function addPath($namespace, $directory = '') {
		if (!$directory) {
			$this->directory = $namespace;
		} else {
			$this->path[$namespace] = $directory;
		}
	}

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
		$file = $this->directory . $filename . '.php';

		$namespace = '';

		$parts = explode('/', $filename);

		foreach ($parts as $part) {
			if (!$namespace) {
				$namespace .= $part;
			} else {
				$namespace .= '/' . $part;
			}

			if (isset($this->path[$namespace])) {
				$file = $this->path[$namespace] . substr($filename, strlen($namespace)) . '.php';
			}
		}

		if (is_file($file)) {
			$_ = [];

			require($file);

			$this->data = array_merge($this->data, $_);

			return $this->data;
		} else {
			trigger_error('Error: Could not load config ' . $filename . '!');
			exit();
		}
	}
}