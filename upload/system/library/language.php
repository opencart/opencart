<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Language class
*/
namespace Opencart\System\Library;
class Language {
	protected $code;
	protected $directory;
	protected $path = [];
	protected $data = [];

	/**
	 * Constructor
	 *
	 * @param    string $code
	 *
	 */
	public function __construct($code) {
		$this->code = $code;
	}

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
     * Get language tex string
     *
     * @param	string	$key
	 * 
	 * @return	string
     */
	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : $key);
	}

	/**
     *  Set language text string
     *
     * @param	string	$key
	 * @param	string	$value
     */	
	public function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	/**
     * All
     *
	 * @return	array
     */
	public function all($prefix = '') {
		if (!$prefix) {
			return $this->data;
		}

		$_ = [];

		$len = strlen($prefix);

		foreach ($this->data as $key => $value) {
			if (substr($key, 0, $len) == $prefix) {
				$_[substr($key, $len + 1)] = $value;
			}
		}

		return $_;
	}

	/**
	 * Clear
	 *
	 * @return	array
	 */
	public function clear() {
		$this->data = [];
	}

	/**
     * Load
     *
     * @param	string	$filename
	 * @param	string	$code 		Language code
	 * 
	 * @return	array
     */
	public function load($filename, $prefix = '', $code = '') {
		if (!$code) {
			$code = $this->code;
		}

		if (!isset($this->cache[$code][$filename])) {
			$file = $this->directory . $code . '/' . $filename . '.php';

			$namespace = '';

			$parts = explode('/', $filename);

			foreach ($parts as $part) {
				if (!$namespace) {
					$namespace .= $part;
				} else {
					$namespace .= '/' . $part;
				}

				if (isset($this->path[$namespace])) {
					$file = $this->path[$namespace] . $code . substr($filename, strlen($namespace)) . '.php';
				}
			}

			$_ = [];

			if (is_file($file)) {
				require($file);
			}

			$this->cache[$code][$filename] = $_;
		} else {
			$_ = $this->cache[$code][$filename];
		}

		if ($prefix) {
			foreach ($_ as $key => $value) {
				$_[$prefix . '_' . $key] = $value;

				unset($_[$key]);
			}
		}

		$this->data = array_merge($this->data, $_);

		return $this->data;
	}
}