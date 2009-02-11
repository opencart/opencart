<?php
final class Router {
	protected $class;
	protected $method;
	protected $args = array();

	public function __construct($route) {
		$path = '';
		
		$parts = explode('/', $route);
		
		foreach ($parts as $part) {
			$path .= $part;
			
			if (is_dir(DIR_APPLICATION . 'controller/' . $path)) {
				$path .= '/';
				
				array_shift($parts);
				
				continue;
			}
			
			if (is_file(DIR_APPLICATION . 'controller/' . $path . '.php')) {
				$this->class = $path;

				array_shift($parts);
				
				break;
			}			
		}

		$method = array_shift($parts);
				
		if ($method) {
			$this->method = $method;
		} else {
			$this->method = 'index';
		}
	}

	public function getClass() {
		return $this->class;
	}
	
	public function getMethod() {
		return $this->method;
	}
	
	public function getArgs() {
		return $this->args;
	}
}
?>