<?php
/**
 * @package        OpenCart
 * @author         Daniel Kerr
 * @copyright      Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license        https://opensource.org/licenses/GPL-3.0
 * @link           https://www.opencart.com
 */
namespace Opencart\System\Engine;
/**
 * Class Factory
 *
 * @object \Opencart\System\Engine\Registry
 */
class Factory {
	/**
	 * @var object|\Opencart\System\Engine\Registry
	 */
	protected $registry;

	/**
	 * Constructor
	 *
	 * @param object $registry
	 */
	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->registry = $registry;
	}

	/**
	 * Controller
	 *
	 * https://wiki.php.net/rfc/variadics
	 *
	 * @param string $route
	 * @param mixed  $args
	 *
	 * @return mixed
	 */
	public function controller(string $route): object {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_|\/\.]/', '', str_replace('|', '.', $route));

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
	 * https://wiki.php.net/rfc/variadics
	 *
	 * @param string $route
	 *
	 * @return void
	 */
	public function model(string $route): object {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', $route);

		// Generate the class
		$class = 'Opencart\\' . $this->registry->get('config')->get('application') . '\Model\\' . str_replace(['_', '/'], ['', '\\'], ucwords($route, '_/'));

		echo $class . '<br/>';

		// Check if the requested model is already stored in the registry.
		if (class_exists($class)) {
			return new $class($this->registry);
		} else {
			throw new \Exception('Error: Could not load model ' . $route . '!');
		}
	}
}