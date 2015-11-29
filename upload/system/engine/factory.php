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
	
	public function model($route) {
		$file  = DIR_APPLICATION . 'model/' . $route . '.php';
		$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $route);

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
				$di->attach($method, $this->closure('model/' . $route));
			}
			
			return $di;
		} else {
			return false;	
		}
	}	
	
	protected function closure($route) {
		return function() use($route) {
			// Get args by reference
			$trace = debug_backtrace();
	
			$args = $trace[0]['args'];				

			// Trigger the pre events
			$result = $this->registry->get('event')->trigger($route . '/' . $trace[3]['function'] . '/before', array('reference' => &$args));
			
			if (!is_null($result)) {
				return $result;
			}
			
			$class = preg_replace('/[^a-zA-Z0-9]/', '', $route);
	
			$model = new $class($this->registry);
				
			$output = call_user_func_array(array($model, $trace[3]['function']), $args);
			
			// Trigger the post events
			$result = $this->registry->get('event')->trigger($route . '/' . $trace[3]['function'] . '/after', array('reference' => array(&$output)));
			
			if (!is_null($result)) {
				return $result;
			}
			
			return $output;
		};
	}	
}