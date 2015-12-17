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

		//echo $this->route . '<br/>';

		$file = DIR_APPLICATION . 'controller/' . $this->route . '.php';		
		$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $this->route);
		
		// Initialize the class
		if (is_file($file)) {
			include_once($file);
		
			$controller = new $class($registry);
		} else {
			return false;
		}
		
		// Now we have the controller we want to check if the corresponding number of arguments matches the method being called.
		//$reflection = new ReflectionObject($controller);

		//$parameters = $reflection->getMethod($this->method)->getParameters();

		//if (count($parameters) != count($args)) {
			//return false;
		//}

		//print_r($parameters);

		// Call the method if set
		if (method_exists($controller, $this->method)) {
			return call_user_func_array(array($controller, $this->method), $args);
		} else {
			return false;
		}
	}
}