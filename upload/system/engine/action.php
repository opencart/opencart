<?php
class Action {
	private $route;
	private $method = 'index';

	public function __construct($route) {
		$parts = explode('/', str_replace('../', '', (string)$route));

		// Break apart the route
		while ($parts) {
			$file = DIR_APPLICATION . 'controller/' . implode('/', $parts) . '.php';

			if (is_file($file)) {
				$this->route = implode('/', $parts);
				break;
			} else {
				$this->method = array_pop($parts);
			}
		}
	}

	public function execute($registry, $args = array()) {
		// Stop any magical methods being called
		if (substr($this->method, 0, 2) == '__') {
			return false;
		}

		$controller = $registry->get('factory')->controller($this->route);

		if ($controller && method_exists($controller, $this->method)) {
			return call_user_func_array(array($controller, $this->method), $args);
		} else {
			return false;
		}
	}
}