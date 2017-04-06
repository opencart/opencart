<?php
final class Loader {
	protected $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function controller($route, $data = array()) {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		
		$output = null;
		
		// Trigger the pre events
		$result = $this->registry->get('event')->trigger('controller/' . $route . '/before', array(&$route, &$data, &$output));
		
		if ($result) {
			return $result;
		}
		
		if (!$output) {
			$action = new Action($route);
			$output = $action->execute($this->registry, array(&$data));
		}
			
		// Trigger the post events
		$result = $this->registry->get('event')->trigger('controller/' . $route . '/after', array(&$route, &$data, &$output));
		
		if ($output instanceof Exception) {
			return false;
		}

		return $output;
	}
	
	public function model($route) {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		
		// Trigger the pre events
		$this->registry->get('event')->trigger('model/' . $route . '/before', array(&$route));
		
		if (!$this->registry->has('model_' . str_replace(array('/', '-', '.'), array('_', '', ''), $route))) {
			$file  = DIR_APPLICATION . 'model/' . $route . '.php';
			$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $route);
			
			if (is_file($file)) {
				include_once($file);
	
				$proxy = new Proxy();
				
				foreach (get_class_methods($class) as $method) {
					$proxy->{$method} = $this->callback($this->registry, $route . '/' . $method);
				}
				
				$this->registry->set('model_' . str_replace(array('/', '-', '.'), array('_', '', ''), (string)$route), $proxy);
			} else {
				throw new \Exception('Error: Could not load model ' . $route . '!');
			}
		}
		
		// Trigger the post events
		$this->registry->get('event')->trigger('model/' . $route . '/after', array(&$route));
	}

	public function view($route, $data = array()) {
		$output = null;
		
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		
		// Trigger the pre events
		$result = $this->registry->get('event')->trigger('view/' . $route . '/before', array(&$route, &$data, &$output));
		
		if ($result) {
			return $result;
		}
		
		if (!$output) {
			$template = new Template($this->registry->get('config')->get('template_type'));
			
			foreach ($data as $key => $value) {
				$template->set($key, $value);
			}
		
			$output = $template->render($route . '.tpl');
		}
		
		// Trigger the post events
		$result = $this->registry->get('event')->trigger('view/' . $route . '/after', array(&$route, &$data, &$output));
		
		if ($result) {
			return $result;
		}
		
		return $output;
	}

	public function library($route) {
		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
			
		$file = DIR_SYSTEM . 'library/' . $route . '.php';
		$class = str_replace('/', '\\', $route);

		if (is_file($file)) {
			include_once($file);

			$this->registry->set(basename($route), new $class($this->registry));
		} else {
			throw new \Exception('Error: Could not load library ' . $route . '!');
		}
	}
	
	public function helper($route) {
		$file = DIR_SYSTEM . 'helper/' . preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route) . '.php';

		if (is_file($file)) {
			include_once($file);
		} else {
			throw new \Exception('Error: Could not load helper ' . $route . '!');
		}
	}
	
	public function config($route) {
		$this->registry->get('event')->trigger('config/' . $route . '/before', array(&$route));
		
		$this->registry->get('config')->load($route);
		
		$this->registry->get('event')->trigger('config/' . $route . '/after', array(&$route));
	}

	public function language($route) {
		$output = null;
		
		$this->registry->get('event')->trigger('language/' . $route . '/before', array(&$route, &$output));
		
		$output = $this->registry->get('language')->load($route);
		
		$this->registry->get('event')->trigger('language/' . $route . '/after', array(&$route, &$output));
		
		return $output;
	}
	
	protected function callback($registry, $route) {
		return function($args) use($registry, &$route) {
			static $model = array(); 			
			
			$output = null;
			
			// Trigger the pre events
			$result = $registry->get('event')->trigger('model/' . $route . '/before', array(&$route, &$args, &$output));
			
			if ($result) {
				return $result;
			}
			
			// Store the model object
			if (!isset($model[$route])) {
				$file = DIR_APPLICATION . 'model/' .  substr($route, 0, strrpos($route, '/')) . '.php';
				$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', substr($route, 0, strrpos($route, '/')));

				if (is_file($file)) {
					include_once($file);
				
					$model[$route] = new $class($registry);
				} else {
					throw new \Exception('Error: Could not load model ' . substr($route, 0, strrpos($route, '/')) . '!');
				}
			}

			$method = substr($route, strrpos($route, '/') + 1);
			
			$callable = array($model[$route], $method);

			if (is_callable($callable)) {
				$output = call_user_func_array($callable, $args);
			} else {
				throw new \Exception('Error: Could not call model/' . $route . '!');
			}
			
			// Trigger the post events
			$result = $registry->get('event')->trigger('model/' . $route . '/after', array(&$route, &$args, &$output));
			
			if ($result) {
				return $result;
			}
						
			return $output;
		};
	}	
}