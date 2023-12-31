<?php
/**
 * @package     OpenCart
 *
 * @author      Daniel Kerr
 * @copyright   Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license     https://opensource.org/licenses/GPL-3.0
 *
 * @see        https://www.opencart.com
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
	 * @var string
	 */
	private string $method;

	/**
	 * Constructor
	 *
	 * @param string $route
	 */
	public function __construct(string $route) {
		$route = preg_replace('/[^a-zA-Z0-9_|\/\.]/', '', $route);

		$pos = strrpos($route, '.');

		if ($pos !== false) {
			$this->route = substr($route, 0, $pos);
			$this->method = substr($route, $pos + 1);
		} else {
			$this->route = $route;
			$this->method = 'index';
		}
	}

	/**
	 * getId
	 *
	 * @return string
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
		// Stop any magical methods being called
		if (substr($this->method, 0, 2) == '__') {
			return new \Exception('Error: Calls to magic methods are not allowed!');
		}

		// Create a key to store the controller object
		$key = 'controller_' . str_replace('/', '_', $this->route);

		if (!$registry->has($key)) {
			// Initialize the class
			$controller = $registry->get('factory')->controller($this->route);

			// Store object
			$registry->set($key, $controller);
		} else {
			$controller = $registry->get($key);
		}

		// If action cannot be executed, we return an action error object.
		if ($controller instanceof \Exception) {
			return new \Exception('Error: Could not call route ' . $this->route . '!');
		}

		$callable = [$controller, $this->method];

		if (is_callable($callable)) {
			return $callable(...$args);
		} else {
			return new \Exception('Error: Could not call route ' . $this->route . '!');
		}
	}
}
