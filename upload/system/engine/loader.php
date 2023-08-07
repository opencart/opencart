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
 * Class Loader
 */
class Loader {
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
	 * __get
	 *
	 * https://www.php.net/manual/en/language.oop5.overloading.php#object.get
	 *
	 * @param string $key
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
	 * @param string $key
	 * @param object $value
	 *
	 * @return    void
	 */
	public function __set(string $key, object $value): void {
		$this->registry->set($key, $value);
	}

	/**
	 * Controller
	 *
	 * https://wiki.php.net/rfc/variadics
	 *
	 * @param string $route
	 * @param array  $data
	 *
	 * @return    mixed
	 */
	public function controller(string $route, mixed ...$args): mixed {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_|\/\.]/', '', str_replace('|', '.', $route));

		$output = '';

		// Keep the original trigger
		$action = new \Opencart\System\Engine\Action($route);

		while ($action) {
			$route = $action->getId();

			// Trigger the pre events
			$result = $this->event->trigger('controller/' . $route . '/before', [&$route, &$args]);

			if ($result instanceof \Opencart\System\Engine\Action) {
				$action = $result;
			}

			// Execute action
			$result = $action->execute($this->registry, $args);

			// Make action a non-object so it's not infinitely looping
			$action = '';

			// Action object returned then we keep the loop going
			if ($result instanceof \Opencart\System\Engine\Action) {
				$action = $result;
			}

			// If not an object then it's the output
			if (!$action) {
				$output = $result;
			}

			// Trigger the post events
			$result = $this->event->trigger('controller/' . $route . '/after', [&$route, &$args, &$output]);

			if ($result instanceof \Opencart\System\Engine\Action) {
				$action = $result;
			}
		}

		return $output;
	}

	/**
	 * Model
	 *
	 * @param string $route
	 *
	 * @return     void
	 */
	public function model(string $route): void {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', $route);

		// Converting a route path to a class name
		$class = 'Opencart\\' . $this->config->get('application') . '\Model\\' . str_replace(['_', '/'], ['', '\\'], ucwords($route, '_/'));

		// Create a key to store the model object
		$key = 'model_' . str_replace('/', '_', $route);

		// Check if the requested model is already stored in the registry.
		if (!$this->registry->has($key)) {
			if (class_exists($class)) {
				$model = new $class($this->registry);

				$proxy = new \Opencart\System\Engine\Proxy();

				foreach (get_class_methods($model) as $method) {
					if ((substr($method, 0, 2) != '__') && is_callable($class, $method)) {
						// Grab args using function because we don't know the number of args being passed.
						// https://www.php.net/manual/en/functions.arguments.php#functions.variable-arg-list
						// https://wiki.php.net/rfc/variadics
						$proxy->{$method} = function (mixed &...$args) use ($route, $model, $method): mixed {
							$route = $route . '/' . $method;

							$output = '';

							// Trigger the pre events
							$result = $this->event->trigger('model/' . $route . '/before', [&$route, &$args]);

							if ($result) {
								$output = $result;
							}

							if (!$output) {
								// Get the method to be used
								$callable = [$model, $method];

								if (is_callable($callable)) {
									$output = call_user_func_array($callable, $args);
								} else {
									throw new \Exception('Error: Could not call model/' . $route . '!');
								}
							}

							// Trigger the post events
							$result = $this->event->trigger('model/' . $route . '/after', [&$route, &$args, &$output]);

							if ($result) {
								$output = $result;
							}

							return $output;
						};
					}
				}

				$this->registry->set($key, $proxy);
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
	 * @param string $route
	 * @param array  $data
	 * @param string $code
	 *
	 * @return   string
	 */
	public function view(string $route, array $data = [], string $code = ''): string {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', $route);

		$output = '';

		// Trigger the pre events
		$result = $this->event->trigger('view/' . $route . '/before', [&$route, &$data, &$code]);

		if ($result) {
			$output = $result;
		}

		if (!$output) {
			// Make sure it's only the last event that returns an output if required.
			$output = $this->template->render($route, $data, $code);
		}

		// Trigger the post events
		$result = $this->event->trigger('view/' . $route . '/after', [&$route, &$data, &$output]);

		if ($result) {
			$output = $result;
		}

		return $output;
	}

	/**
	 * Language
	 *
	 * @param string $route
	 * @param string $prefix
	 * @param string $code
	 *
	 * @return    array
	 */
	public function language(string $route, string $prefix = '', string $code = ''): array {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', $route);

		$output = [];

		// Trigger the pre events
		$result = $this->event->trigger('language/' . $route . '/before', [&$route, &$prefix, &$code]);

		if ($result) {
			$output = $result;
		}

		if (!$output) {
			$output = $this->language->load($route, $prefix, $code);
		}

		// Trigger the post events
		$result = $this->event->trigger('language/' . $route . '/after', [&$route, &$prefix, &$code, &$output]);

		if ($result) {
			$output = $result;
		}

		return $output;
	}

	/**
	 * Config
	 *
	 * @param string $route
	 *
	 * @return     array
	 */
	public function config(string $route): array {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', $route);

		$output = [];

		// Trigger the pre events
		$result = $this->event->trigger('config/' . $route . '/before', [&$route]);

		if ($result) {
			$output = $result;
		}

		if (!$output) {
			$output = $this->config->load($route);
		}

		// Trigger the post events
		$result = $this->event->trigger('config/' . $route . '/after', [&$route, &$output]);

		if ($result) {
			$output = $result;
		}

		return $output;
	}

	/**
	 * Helper
	 *
	 * @param string $route
	 *
	 * @return     void
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
}
