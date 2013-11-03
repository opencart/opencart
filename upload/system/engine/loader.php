<?php
final class Loader {
	protected $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function controller($route, $args = array()) {
		$path = '';
		
		$parts = explode('/', str_replace(array('../', '..\\', '..'), '', (string)$route));
		
		foreach ($parts as $part) {
			$path .= $part;
			
			if (is_dir(DIR_APPLICATION . 'controller/' . $path)) {
				$path .= '/';
				
				array_shift($parts);
				
				continue;
			}
			
			$file = DIR_APPLICATION . 'controller/' .  $path . '.php';
			
			if (is_file($file)) {
				$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $path);

				array_shift($parts);
				
				break;
			}
		}
			
		$method = array_shift($parts);
				
		if (!$method) {
			$method = 'index';
		}
					
		if (file_exists($file)) { 
			include_once($file);
			
			$controller = new $class($this->registry);
					
			if (is_callable(array($controller, $method))) {
				return call_user_func_array(array($controller, $method), $args);
			} else {				
				return false;
			}
		} else {
			trigger_error('Error: Could not load controller ' . $file . '!');
			exit();
		}
	}
		
	public function model($model) {
		$file = DIR_APPLICATION . 'model/' . $model . '.php';
		$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
				
		if (file_exists($file)) { 
			include_once($file);

			$this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
		} else {
			trigger_error('Error: Could not load model ' . $file . '!');
			exit();
		}
	}
	
	public function view($template, $data = array()) {
		$file = DIR_TEMPLATE . $template;
		
		if (file_exists($file)) {
			foreach ($data as $key => $value) {
				if (is_object($value)) {
					${$key} = $value->index(); 
				} else {
					${$key} = $value;
				}
			}

			ob_start();

			require($file);

			$output = ob_get_contents();

			ob_end_clean();

			return $output;
		} else {
			trigger_error('Error: Could not load template ' . $file . '!');
			exit();
		}
	}

	public function library($library) {
		$file = DIR_SYSTEM . 'library/' . $library . '.php';

		if (file_exists($file)) {
			include_once($file);
		} else {
			trigger_error('Error: Could not load library ' . $file . '!');
			exit();
		}
	}
	
	public function helper($helper) {
		$file = DIR_SYSTEM . 'helper/' . $helper . '.php';

		if (file_exists($file)) {
			include_once($file);
		} else {
			trigger_error('Error: Could not load helper ' . $file . '!');
			exit();
		}
	}
	
	public function database($type, $hostname, $username, $password, $database) {
		$file = DIR_SYSTEM . 'library/' . $driver . '.php';

		if (file_exists($file)) {
			include_once($file);

			$this->registry->set($type, new $class($type, $hostname, $username, $password, $database));
		} else {
			trigger_error('Error: Could not load database ' . $file . '!');
			exit();
		}
	}

	public function config($config) {
		$this->config->load($config);
	}

	public function language($language) {
		return $this->language->load($language);
	}
} 
?>