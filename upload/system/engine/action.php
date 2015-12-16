<?php
class Action {
	private $file;
	private $class;
	private $method = 'index';

	public function __construct($route) {
		$parts = explode('/', str_replace('../', '', (string)$route));

		// Break apart the route
		while ($parts) {
			$file = DIR_APPLICATION . 'controller/' . implode('/', $parts) . '.php';

			if (is_file($file)) {
				$this->file  = DIR_APPLICATION . 'controller/' . implode('/', $parts) . '.php';		
				$this->class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', implode('/', $parts));
				
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
		
		// Initialize the class
		if (is_file($this->file)) {
			include_once($this->file);
		
			$controller = new $this->class($registry);
		} else {
			return false;
		}


		//print_r($args);
		
		// Now we have the controller we want to check if the corrasponding number of arguments matches the method being called.

		$reflection = new ReflectionObject($controller);

		$parameters = $reflection->getMethod($this->method)->getParameters();

		foreach ($perameters as $perameter) {
			echo $perameter->name . "\n";
		}

		if (count($perameters) != count($args)) {
			
		}

		print_r($perameters);

		// Call the method if set
		if (method_exists($controller, $this->method)) {
			return call_user_func_array(array($controller, $this->method), $args);
		} else {
			return false;
		}
	}
}