<?php
/**
 * @package        OpenCart
 *
 * @author         Daniel Kerr
 * @copyright      Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license        https://opensource.org/licenses/GPL-3.0
 *
 * @see           https://www.opencart.com
 */
namespace Opencart\System\Engine;
/**
 * Class Factory
 */
class Factory {
	/**
	 * @var \Opencart\System\Engine\Registry
	 */
	protected \Opencart\System\Engine\Registry $registry;

	/**
	 * Constructor
	 *
	 * @param \Opencart\System\Engine\Registry $registry
	 */
	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->registry = $registry;
	}

	/**
	 * Controller
	 *
	 * @param string $route
	 *
	 * @return \Exception|\Opencart\System\Engine\Controller
	 */
	public function controller(string $route): object {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', $route);

		// Class path
		$class = 'Opencart\\' . $this->registry->get('config')->get('application') . '\Controller\\' . str_replace(['_', '/'], ['', '\\'], ucwords($route, '_/'));

		if (class_exists($class)) {
			return new $class($this->registry);
		} else {
			return new \Exception('Error: Could not load controller ' . $route . '!');
		}
	}

	/**
	 * Model
	 *
	 * @param string $route
	 *
	 * @return \Opencart\System\Engine\Model
	 */
	public function model(string $route): object {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', $route);

		// Generate the class
		$class = 'Opencart\\' . $this->registry->get('config')->get('application') . '\Model\\' . str_replace(['_', '/'], ['', '\\'], ucwords($route, '_/'));

		// Check if the requested model is already stored in the registry.
		if (class_exists($class)) {
			return new $class($this->registry);
		} else {
			return new \Exception('Error: Could not load model ' . $route . '!');
		}
	}

	/**
	 * Library
	 *
	 * @param string       $route
	 * @param array<mixed> $args
	 *
	 * @return object
	 */
	public function library(string $route, array $args): object {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', $route);

		// Generate the class
		$class = 'Opencart\System\Library\\' . str_replace(['_', '/'], ['', '\\'], ucwords($route, '_/'));

		// Check if the requested model is already stored in the registry.
		if (class_exists($class)) {
			return new $class(...$args);
		} else {
			return new \Exception('Error: Could not load library ' . $route . '!');
		}
	}
}
