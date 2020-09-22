<?php
/**
 * @package     OpenCart
 * @author      Daniel Kerr
 * @copyright   Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license     https://opensource.org/licenses/GPL-3.0
 * @link        https://www.opencart.com
 */

/**
 * Action class
 */
namespace Opencart\System\Engine;
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

		$class = 'Opencart\Application\Controller\\' . str_replace(['_', '/'], ['', '\\'], ucwords($this->route, '_/'));

		if (substr($route, 0, 10) == 'extension/') {
			//echo 'Action' . "\n";
			//echo '$route ' . $route . "\n";
			//echo '$class ' . $class . "\n";
		}

		if (class_exists($class)) {
			$this->class = $class;
		} else {
			$class = substr($class, 0, strrpos($class, '\\'));

			if (class_exists($class)) {
				$this->class = $class;
				$this->method = substr($this->route, strrpos($this->route, '/') + 1);
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
	 *
	 * Execute Action
	 *
	 * @param    object $registry
	 * @param    array $args
	 *
	 * @return	mixed
	 */
	public function execute(Registry $registry, array &$args = []) {
		// Stop any magical methods being called
		if (substr($this->method, 0, 2) == '__') {
			return new \Exception('Error: Calls to magic methods are not allowed!');
		}

		// Initialize the class
		if ($this->class) {
			return call_user_func_array([new $this->class($registry), $this->method], $args);
		} else {
			return new \Exception('Error: Could not call route ' . $this->route . ' class ' . $this->class . '!');
		}
	}
}
