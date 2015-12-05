<?php
final class Factory {
	protected $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function controller($route) {
		$file  = DIR_APPLICATION . 'controller/' . $route . '.php';
		$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $route);

		if (file_exists($file)) {
			include_once($file);
		
			return new $class($this->registry);
		} else {
			return false;
		}
	}
	
	public function mockModel($route) {
		$file  = DIR_APPLICATION . 'model/' . $route . '.php';
		$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $route);

		if (file_exists($file)) {
			include_once($file);
			
			$di = new DIContainer($this->registry);
		
			foreach (get_class_methods($class) as $method) {
				$di->attach($method, $this->closure($route));
			} 
			
			return $di;
		} else {
			return false;	
		}
	}	
	
	protected function closure($route) {
		return function($method, $args) use($route) {
			// Reference the route
			$route = $route . '/' . $method;
			$route = &$route;

			// Trigger the pre events
			$result = $this->registry->get('event')->trigger('model/' . $route . '/' . $method . '/before', array($route, &$args));
			
			if (!is_null($result)) {
				return $result;
			}
			
			//$output = $this->registry->get('load')->call($route);
			
			$method = substr($route, strrpos($route, '/') + 1);
			$route = substr($route, 0, strrpos($route, '/'));
			
			$file  = DIR_APPLICATION . 'model/' . $route . '.php';
			$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $route);
	
			if (file_exists($file)) {
				include_once($file);
			
				$model = new $class($this->registry);
			} else {
				trigger_error('Error: Could not load model ' . $route . '!');
				exit();
			}
						
			$output = call_user_func_array(array($model, $method), $args);
			
			// Trigger the post events
			$result = $this->registry->get('event')->trigger('model/' . $route . '/' . $method . '/after', array(&$output));
			
			if (!is_null($result)) {
				return $result;
			}
			
			return $output;
		};
	}	
}