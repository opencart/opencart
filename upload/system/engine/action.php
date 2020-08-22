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
use Application;
class Action {
	private $route;
	private $class;
	private $method = 'index';

	/**
	 * Constructor
	 *
	 * @param    string $route
	 */
	public function __construct(string $route) {
		$this->route = preg_replace('/[^a-zA-Z0-9_\/]/', '', $route);

		// Converting a route path to a class name
		$class = 'Application\Controller\\' . str_replace(array('_', '/'), array('', '\\'), ucwords($route, '_/'));

		if (class_exists($class)) {
			$this->class = $class;
		} else {
			$this->class = substr($class, 0, strrpos($class, '\\'));
			$this->method = substr($route, strrpos($route, '/') + 1);
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
	public function execute(Registry $registry, array &$args = []) {
		// Stop any magical methods being called
		if (substr($this->method, 0, 2) == '__') {
			return new \Exception('Error: Calls to magic methods are not allowed!');
		}

		// Initialize the class
		if ($this->class) {
			$controller = new $this->class($registry);
		} else {
			return new \Exception('Error: Could not call ' . $this->route . '!');
		}

		$callable = array($controller, $this->method);

		if (is_callable($callable)) {
			return call_user_func_array($callable, $args);
		} else {
			return new \Exception('Error: Could not call ' . $this->route . '!');
		}
	}
}
