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
	private string $route;
	private string $class;
	private string $method;

	/**
	 * Constructor
	 *
	 * @param    string $route
	 */
	public function __construct(string $route) {
		$this->route = preg_replace('/[^a-zA-Z0-9_|\/\.]/', '', $route);

		$pos = strrpos($this->route, '.');

		if ($pos === false) {
			$this->class  = 'Controller\\' . str_replace(['_', '/'], ['', '\\'], ucwords($this->route, '_/'));
			$this->method = 'index';
		} else {
			$this->class  = 'Controller\\' . str_replace(['_', '/'], ['', '\\'], ucwords(substr($this->route, 0, $pos), '_/'));
			$this->method = substr($this->route, $pos + 1);
		}
	}

	/**
	 * Identify Action
	 *
	 * @return    string
	 *
	 */
	public function getId(): string {
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
	public function execute(\Opencart\System\Engine\Registry $registry, array &$args = []): mixed {
		// Stop any magical methods being called
		if (substr($this->method, 0, 2) == '__') {
			return new \Exception('Error: Calls to magic methods are not allowed!');
		}

		// Get the current namespace being used by the config
		$class = 'Opencart\\' . $registry->get('config')->get('application') . '\\' . $this->class;

		// Initialize the class
		if (class_exists($class)) {
			$controller = new $class($registry);
		} else {
			return new \Exception('Error: Could not call route ' . $this->route . '!');
		}

		if (is_callable([$controller, $this->method])) {
			return call_user_func_array([$controller, $this->method], $args);
		} else {
			return new \Exception('Error: Could not call route ' . $this->route . '!');
		}
	}
}
