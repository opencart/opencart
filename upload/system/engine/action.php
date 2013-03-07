<?php
final class Action {
	protected $route;
	protected $args;
	protected $method;
	protected $args = array();

	public function __construct($route, $args = array()) {
		$this->route = $route;
		$this->args = $args;
		
		$path = '';

		$parts = explode('/', str_replace('../', '', (string)$route));

		foreach ($parts as $part) { 
			$path .= $part;
			
			if (is_dir(DIR_APPLICATION . 'controller/' . $path)) {
				$path .= '/';

				array_shift($parts);

				continue;
			}

			if (is_file(DIR_APPLICATION . 'controller/' . str_replace(array('../', '..\\', '..'), '', $path) . '.php')) {
				$this->file = DIR_APPLICATION . 'controller/' . str_replace(array('../', '..\\', '..'), '', $path) . '.php';

				$this->class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $path);

				array_shift($parts);

				break;
			}
		}

		if ($args) {
			$this->args = $args;
		}

		$method = array_shift($parts);

		if ($method) {
			$this->method = $method;
		} else {
			$this->method = 'index';
		}
	}

	public function execute($registry) {
		
		
		
		
		
		if (file_exists($this->getFile())) {
			require_once($action->getFile());

			$class = $action->getClass();

			$controller = new $class($registry);

			$controller->{$action->getMethod()}($action->getArgs());

			return $controller;
		} else {
			return false;
		}
	}
}
?>