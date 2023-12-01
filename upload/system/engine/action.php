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
 * Class Action
 *
 * @package Opencart\System\Engine
 */
class Action {
	/**
	 * @var string
	 */
	private string $route;

	/**
	 * Constructor
	 *
	 * @param string $route
	 */
	public function __construct(string $route) {
		$this->route = preg_replace('/[^a-zA-Z0-9_|\/\.]/', '', $route);
	}

	/**
	 * getId
	 *
	 * @return string
	 *
	 */
	public function getId(): string {
		return $this->route;
	}

	/**
	 * Execute
	 *
	 * @param object $registry
	 * @param array  $args
	 *
	 * @return mixed
	 */
	public function execute(\Opencart\System\Engine\Registry $registry, array &$args = []) {
		$pos = strrpos($this->route, '.');

		if ($pos !== false) {
			$route = substr($this->route, 0, $pos);
			$method = substr($this->route, $pos + 1);
		} else {
			$route = $this->route;
			$method = 'index';
		}

		// Stop any magical methods being called
		if (substr($method, 0, 2) == '__') {
			return new \Exception('Error: Calls to magic methods are not allowed!');
		}

		// Create a key to store the controller object
		$key = 'controller_' . str_replace('/', '_', $route);

		if (!$registry->has($key)) {
			// Initialize the class
			$controller = $registry->get('factory')->controller($route);

			// Store object
			$registry->set($key, $controller);
		} else {
			$controller = $registry->get($key);
		}

		// If action cannot be executed, we return an action error object.
		if ($controller instanceof \Exception) {
			return new \Exception('Error: Could not call route ' . $this->route . '!');
		}

		$callable = [$controller, $method];

		if (is_callable($callable)) {
			return call_user_func_array($callable, $args);
		} else {
			return new \Exception('Error: Could not call route ' . $this->route . '!');
		}
	}
}
