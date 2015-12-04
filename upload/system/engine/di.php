<?php
class DIContainer {
	protected $registry;
	private $route;
	private $data = array();
	
	public function __construct($registry, $route) {
		$this->registry = $registry;
		$this->route = $route;
	}
	
	public function call($route, $args = array()) {
		//echo $route;
		echo substr($route, 0, strrpos($route, '/'));
		
		$file  = DIR_APPLICATION . 'model/' . substr($route, 0, strrpos($route, '/')) . '.php';
		$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', substr($route, 0, strrpos($route, '/')));

		if (is_file($file)) {
			include_once($file);
		
			$model = new $class($this->registry);
		} else {
			trigger_error('Error: Could not load model ' . substr($route, 0, strrpos($route, '/')) . '!');
			exit();
		}
		
		// Call the method if set
		if (method_exists($model, $method)) {
			$method = substr($route, strrpos($route, '/') + 1);
		} else {
			return false;
		}
		
		
		return call_user_func_array(array($model, $method), $args);	
	}
	
	public function __call($method, $args = array()) {
		// Reference the route
		$route = $this->route . '/' . $method;

		// Trigger the pre events
		$result = $this->registry->get('event')->trigger('model/' . $route . '/before', array($route, &$args));
		
		if (!is_null($result)) {
			return $result;
		}
		
		$output = $this->call($route, $args);
		
		// Trigger the post events
		$result = $this->registry->get('event')->trigger('model/' . $route . '/after', array(&$output));
		
		if (!is_null($result)) {
			return $result;
		}
		
		return $output;		
	}
}