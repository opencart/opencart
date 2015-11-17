<?php
final class Loader {
	public $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
	
	public function controller($route) {
		// Get args by reference
		$trace = debug_backtrace();

		$args = $trace[0]['args'];
		
		// Sanitize the call
		$route = str_replace('../', '', (string)$args[0]);
	
		// Trigger the pre events
		$result = $this->registry->get('event')->trigger('controller/' . $route . '/before', $args);
		
		if (!is_null($result)) {
			return $result;
		}
			
		$action = new Action($args[0], $this->registry);
		
		array_shift($args);
				
		$output = $action->execute($args);

		// Trigger the post events
		$result = $this->registry->get('event')->trigger('controller/' . $route . '/after', $output);
		
		if (!is_null($result)) {
			return $result;
		}
				
		return $output;
	}
	
	public function model($model) {
		// Sanitize the call
		$model = str_replace('../', '', (string)$model);

		if (!$this->registry->has('model_' . str_replace('/', '_', $model))) {

			$file = DIR_APPLICATION . 'model/' . $model . '.php';
			$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
	
			if (file_exists($file)) {
				include_once($file);
				
				$object = new $class($this->registry);

				//$mock = new Interceptor($object);

				//$interceptor->addPreAction(new Action('override/test/model', $this->registry));	
									
				/*			
				$interceptor = new Interceptor($object);
				
				$interceptor->addPreAction(new Action('override/test/model', $this->registry));
								
				// Call any events
				//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "event WHERE `trigger` LIKE 'catalog/model/" . $this->db->escape($model) . "/%'");
				
				foreach ($query->rows as $result) {
					//$interceptor->addPreAction(substr($result['trigger'], strrpos($result['trigger'], '/') + 1), new Action($result['action'], $this->registry));
				}
				
				//$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "event WHERE `trigger` = 'catalog/model/" . $this->db->escape($model) . "/%'");
				
				foreach ($query->rows as $result) {
					//$interceptor->addPostAction(substr($result['trigger'], strrpos($result['trigger'], '/') + 1), new Action($result['action']));
				}
				*/	
		
		
				$this->registry->set('model_' . str_replace('/', '_', $model), $object);
			} else {
				trigger_error('Error: Could not load model ' . $file . '!');
				exit();
			}
		}
	}

	public function view($view, $data = array()) {
		// Get args by reference
		$trace = debug_backtrace();

		$args = $trace[0]['args'];		
		
		// Sanitize the call
		$view = str_replace('../', '', (string)$args[0]);

		$result = $this->registry->get('event')->trigger('view/' . $view . '/before', $args);
		
		if (!is_null($result)) {
			return $result;
		}
		
		$template = new Template('basic');
		
		foreach ($data as $key => $value) {
			$template->set($key, $value);
		}
		
		$output = $template->render($args[0]);	

		$result = $this->registry->get('event')->trigger('view/' . $view . '/after', array(&$output));
		
		if (!is_null($result)) {
			return $result;
		}
		
		return $output;
	}

	public function helper($helper) {
		$file = DIR_SYSTEM . 'helper/' . str_replace('../', '', (string)$helper) . '.php';

		if (file_exists($file)) {
			include_once($file);
		} else {
			trigger_error('Error: Could not load helper ' . $file . '!');
			exit();
		}
	}

	public function config($config) {
		$this->registry->get('event')->trigger('config/' . $config . '/before', $config);
		
		$this->registry->get('config')->load($config);
		
		$this->registry->get('event')->trigger('config/' . $config . '/after');
	}

	public function language($language) {
		$this->registry->get('event')->trigger('language/' . $language . '/before', $language);
		
		$this->registry->get('language')->load($language);
		
		$this->registry->get('event')->trigger('language/' . $language . '/after');
	}
}