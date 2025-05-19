<?php
/**
 * @package     OpenCart
 *
 * @author      Daniel Kerr
 * @copyright   Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license     https://opensource.org/licenses/GPL-3.0
 *
 * @see        https://www.opencart.com
 */
namespace Opencart\System\Engine;
/**
 * Class Autoloader
 */
class Autoloader {
	/**
	 * @var array<string, array<string, mixed>>
	 */
	private array $path = [];

	/**
	 * Constructor
	 */
	public function __construct() {
		spl_autoload_register(function(string $class): void {
			$this->load($class);
		});

		spl_autoload_extensions('.php');
	}

	/**
	 * Register
	 *
	 * @param string $namespace
	 * @param string $directory
	 * @param bool   $psr4
	 *
	 * @return void
	 *
	 * @psr-4 filename standard is stupid composer has lower case file structure than its packages have camelcase file names!
	 */
	public function register(string $namespace, string $directory, $psr4 = false): void {
		if (isset($this->path[$namespace])) {
			$this->path[$namespace]['directories'][] = $directory;
		} else {
			$this->path[$namespace] = [
				'directories' => [$directory],
				'psr4'      => $psr4
			];
		}
	}

	/**
	 * Load
	 *
	 * @param string $class
	 *
	 * @return bool
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
				$files = [];

				if (!$this->path[$namespace]['psr4']) {
					foreach ($this->path[$namespace]['directories'] as $directory) {
						$files[] = $directory . trim(str_replace('\\', '/', strtolower(preg_replace('~([a-z])([A-Z]|[0-9])~', '\\1_\\2', substr($class, strlen($namespace))))), '/') . '.php';
					}
				} else {
					foreach ($this->path[$namespace]['directories'] as $directory) {
						$files[] = $directory . trim(str_replace('\\', '/', substr($class, strlen($namespace))), '/') . '.php';
					}
				}
			}
		}

		if (isset($files)) {
			foreach ($files as $file) {
				if (isset($file) && is_file($file)) include_once($file);
			}

			return true;
		}

		return false;
	}
}
