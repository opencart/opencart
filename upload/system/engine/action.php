<?php
/**
 * @package        OpenCart
 * @author        Daniel Kerr
 * @copyright    Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license        https://opensource.org/licenses/GPL-3.0
 * @link        https://www.opencart.com
 */

/**
 * Action class
 */
namespace System\Engine;
class Action {
	private $route;
	private $base;
	private $path;
	private $method = 'index';

	/**
	 * Constructor
	 *
	 * @param    string $route
	 */
	public function __construct($route) {
		$this->route = $route;

		$parts = explode('/', preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route));

		// Break apart the route
		while ($parts) {
			$path = implode('/', $parts);

			$file = DIR_APPLICATION . 'controller/' . $path . '.php';

			if (is_file($file)) {
				$this->path = $path;

				break;
			} else {
				$this->method = array_pop($parts);
			}
		}
	}

	/**
	 * Identify Action
	 *
	 * @return    string
	 *
	 */
	public function getId() {
		return $this->route;
	}

	/**
	 * Execute Action
	 *
	 * @param    object $registry
	 * @param    array $args
	 */
	public function execute($registry, array &$args = array()) {
		// Stop any magical methods being called
		if (substr($this->method, 0, 2) == '__') {
			return new \Exception('Error: Calls to magic methods are not allowed!');
		}

		$class = '\Catalog\Controller\\' . str_replace('/', '\\', $this->path);

		//$parts = explode('/', $this->path);

		//foreach ($parts as $part) {
			//$class .= '\\' . preg_replace('/[^a-zA-Z0-9]/', '', $part);
		//}

		echo 'Action $class: ' . $class . "\n";

		$controller = new $class($registry);

		// Initialize the class
		if (class_exists($class)) {
			$controller = new $class($registry);
		} else {
			return new \Exception('Error: Could not call ' . $this->route . '/' . $this->method . '!');
		}

		$callable = array($controller, $this->method);

		if (is_callable($callable)) {
			return call_user_func_array($callable, $args);
		} else {
			return new \Exception('Error: Could not call ' . $this->route . '/' . $this->method . '!');
		}
	}
}
