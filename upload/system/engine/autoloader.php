<?php
/**
 * @package     OpenCart
 * @author      Daniel Kerr
 * @copyright   Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license     https://opensource.org/licenses/GPL-3.0
 * @link        https://www.opencart.com
 */
namespace Opencart\System\Engine;
/**
 * Class Autoloader
 */
class Autoloader {
	/**
	 * @var array
	 */
	private array $path = [];

	/**
	 * Constructor
	 */
	public function __construct() {
		spl_autoload_register([$this, 'load']);
		spl_autoload_extensions('.php');
	}

	/**
	 * Register
	 *
	 * @param    string  $namespace
	 * @param    string  $directory
	 * @param    bool  $psr4
	 *
	 * @return   void
	 *
	 * @psr-4 filename standard is stupid composer has lower case file structure than its packages have camelcase file names!
	 */	
	public function register(string $namespace, string $directory, $psr4 = false): void {
		$this->path[$namespace] = [
			'directory' => $directory,
			'psr4'      => $psr4
		];
	}
	
	/**
	 * Load
	 *
	 * @param    string  $class
	 *
	 * @return	 bool
	 */
	public function load(string $class): bool {
		$namespace = '';

		$parts = explode('\\', $class);

		foreach ($parts as $part) {
			if (!$namespace) {
				$namespace .= $part;
			} else {
				$namespace .= '\\' . $part;
			}

			if (isset($this->path[$namespace])) {
				if (!$this->path[$namespace]['psr4']) {
					$file = $this->path[$namespace]['directory'] . trim(str_replace('\\', '/', strtolower(preg_replace('~([a-z])([A-Z]|[0-9])~', '\\1_\\2', substr($class, strlen($namespace))))), '/') . '.php';
				} else {
					$file = $this->path[$namespace]['directory'] . trim(str_replace('\\', '/', substr($class, strlen($namespace))), '/') . '.php';
				}
			}
		}

		if (isset($file) && is_file($file)) {
			include_once($file);

			return true;
		} else {
			return false;
		}
	}
}
