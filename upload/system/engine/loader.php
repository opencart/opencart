<?php
/**
 * @package        OpenCart
 * @author        Daniel Kerr
 * @copyright    Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license        https://opensource.org/licenses/GPL-3.0
 * @link        https://www.opencart.com
 */

/**
 * Loader class
 */
namespace Opencart\System\Engine;
use \Opencart\System\Engine\Action as Action;
class Loader {
	protected $registry;

	/**
	 * __construct
	 *
	 * @param    object $registry
	 */
	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->registry = $registry;
	}

	/**
	 * __get
	 *
	 * https://www.php.net/manual/en/language.oop5.overloading.php#object.get
	 *
	 * @param    string $key
	 *
	 * @return   object
	 */
	public function __get(string $key): object {
		return $this->registry->get($key);
	}

	/**
	 * __set
	 *
	 * https://www.php.net/manual/en/language.oop5.overloading.php#object.set
	 *
	 * @param    string $key
	 * @param    object $value
	 *
	 * @return    object
	 */
	public function __set(string $key, object $value): void {
		$this->registry->set($key, $value);
	}

	/**
	 * Controller
	 *
	 * https://wiki.php.net/rfc/variadics
	 *
	 * @param    string $route
	 * @param    array $data
	 *
	 * @return    mixed
	 */
	public function controller(string $route, mixed ...$args): mixed {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_|\/]/', '', $route);

		// Keep the original trigger
		$trigger = $route;

		// Trigger the pre events
		$result = $this->event->trigger('controller/' . $trigger . '/before', [&$route, &$args]);

		// Make sure it's only the last event that returns an output if required.
		if ($result != null && !$result instanceof \Exception) {
			$output = $result;
		} else {
			$action = new \Opencart\System\Engine\Action($route);
			$output = $action->execute($this->registry, $args);
		}

		// Trigger the post events
		$result = $this->event->trigger('controller/' . $trigger . '/after', [&$route, &$args, &$output]);

		if ($result && !$result instanceof \Exception) {
			$output = $result;
		}

		if (!$output instanceof \Exception) {
			return $output;
		}

		return '';
	}
	
	/**
	 * Model
	 *
	 * @param    string $route
	 */
	public function model(string $route): void {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', $route);

		// Check if the requested model is already stored in the registry.
		if (!$this->registry->has('model_' . str_replace('/', '_', $route))) {
			// Converting a route path to a class name
			$class = 'Opencart\\' . $this->config->get('application') . '\Model\\' . str_replace(['_', '/'], ['', '\\'], ucwords($route, '_/'));

			if (class_exists($class)) {
				$proxy = new \Opencart\System\Engine\Proxy();

				// Overriding models is a little harder so we have to use PHP's magic methods.
				foreach (get_class_methods($class) as $method) {
					if ((substr($method, 0, 2) != '__') && is_callable($class, $method))  {
						$proxy->{$method} = $this->callback($route . '/' . $method);
					}
				}

				$this->registry->set('model_' . str_replace('/', '_', $route), $proxy);
			} else {
				throw new \Exception('Error: Could not load model ' . $class . '!');
			}
		}
	}

	/**
	 * View
	 *
	 * Loads the template file and generates the html code.
	 *
	 * @param    string $route
	 * @param    array $data
	 *
	 * @return   string
	 */
	public function view(string $route, array $data = [], string $code = ''): string {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', $route);

		// Keep the original trigger
		$trigger = $route;

		// Trigger the pre events
		$this->event->trigger('view/' . $trigger . '/before', [&$route, &$data, &$code]);

		// Make sure its only the last event that returns an output if required.
		$output = $this->template->render($route, $data, $code);

		// Trigger the post events
		$this->event->trigger('view/' . $trigger . '/after', [&$route, &$data, &$output]);

		return $output;
	}

	/**
	 * Language
	 *
	 * @param    string $route
	 * @param    string $key
	 *
	 * @return    array
	 */
	public function language(string $route, string $prefix = '', string $code = ''): array {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', $route);

		// Keep the original trigger
		$trigger = $route;

		$this->event->trigger('language/' . $trigger . '/before', [&$route, &$prefix, &$code]);

		$data = $this->language->load($route, $prefix, $code);

		$this->event->trigger('language/' . $trigger . '/after', [&$route, &$prefix, &$code, &$data]);

		return $data;
	}

	/**
	 * Library
	 *
	 * Loads library classes
	 *
	 * @param    string $route	The path to the library file.
	 * @param    string $args	A list of arguments to pass into the library object being created.
	 */
	public function library(string $route, array &...$args): object {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', $route);

		// Keep the original trigger
		$trigger = $route;

		$this->event->trigger('library/' . $trigger . '/before', [&$route, &$args]);

		$class = 'Opencart\System\Library\\' . str_replace(['_', '/'], ['', '\\'], ucwords($route, '_/'));

		if (class_exists($class)) {
			$library = new $class(...$args);

			// Create a key to store the library object
			$this->registry->set(str_replace('/', '_', $route), $library);
		} else {
			throw new \Exception('Error: Could not load library ' . $route . '!');
		}

		$this->event->trigger('library/' . $trigger . '/after', [&$route, &$args]);

		return $library;
	}

	/**
	 * Helper
	 *
	 * @param    string $route
	 */
	public function helper(string $route): void {
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', $route);

		if (!str_starts_with($route, 'extension/')) {
			$file = DIR_SYSTEM . 'helper/' . $route . '.php';
		} else {
			$parts = explode('/', substr($route, 10));

			$code = array_shift($parts);

			$file = DIR_EXTENSION . $code . '/system/helper/' . implode('/', $parts) . '.php';
		}

		if (is_file($file)) {
			include_once($file);
		} else {
			throw new \Exception('Error: Could not load helper ' . $route . '!');
		}
	}

	/**
	 * Config
	 *
	 * @param    string $route
	 */
	public function config(string $route): array {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', $route);

		// Keep the original trigger
		$trigger = $route;

		$this->event->trigger('config/' . $trigger . '/before', [&$route]);

		$data = $this->config->load($route);

		$this->event->trigger('config/' . $trigger . '/after', [&$route, &$data]);

		return $data;
	}

	/**
	 * Callback
	 *
	 * https://www.php.net/manual/en/class.closure.php
	 *
	 * @param	string $route
	 *
	 * @return	closure
	 */
	protected function callback(string $route): callable {
		return function (mixed &...$args) use ($route) {
			// Grab args using function because we don't know the number of args being passed.
			// https://www.php.net/manual/en/functions.arguments.php#functions.variable-arg-list
			// https://wiki.php.net/rfc/variadics
			$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', $route);

			// Keep the original trigger
			$trigger = $route;

			// Trigger the pre events
			$result = $this->event->trigger('model/' . $trigger . '/before', [&$route, &$args]);

			if ($result) {
				$output = $result;
			} else {
				// Create a key to store the model object
				$key = substr($route, 0, strrpos($route, '/'));

				// Check if the model has already been initialised or not
				if (!$this->registry->has($key)) {
					// Create the class name from the key
					$class = 'Opencart\\' . $this->config->get('application') . '\Model\\' . str_replace(['_', '/'], ['', '\\'], ucwords($key, '_/'));

					$model = new $class($this->registry);

					$this->registry->set($key, $model);
				} else {
					$model = $this->registry->get($key);
				}

				// Get the method to be used
				$method = substr($route, strrpos($route, '/') + 1);

				$callable = [$model, $method];

				if (is_callable($callable)) {
					$output = call_user_func_array($callable, $args);
				} else {
					throw new \Exception('Error: Could not call model/' . $route . '!');
				}
			}

			// Trigger the post events
			$result = $this->event->trigger('model/' . $trigger . '/after', [&$route, &$args, &$output]);

			if ($result) {
				$output = $result;
			}

			return $output;
		};
	}
}
