<?php
final class Loader {
	protected $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function library($library) {
		$file = DIR_SYSTEM . 'library/' . $library . '.php';

		if (file_exists($file)) {
			include_once($file);
		} else {
			trigger_error('Error: Could not load library ' . $library . '!');
			exit();
		}
	}
	
	public function controller($controller) {
		$file  = DIR_APPLICATION . 'controller/' . $controller . '.php';
		$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $controller);
				
		if (file_exists($file)) { 
			include_once($file);
			
			$controller = new $class($this->registry);

			if (is_callable(array($controller, $action->getMethod()))) {
				$action = call_user_func_array(array($controller, $action->getMethod()), $action->getArgs());
			} else {
				$action = $this->error;
			
				$this->error = '';
			}
						
			//$this->registry->set('controller_' . str_replace('/', '_', $controller
		} else {
			trigger_error('Error: Could not load controller ' . $model . '!');
			exit();
		}
	}
		
	public function model($model) {
		$file  = DIR_APPLICATION . 'model/' . $model . '.php';
		$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
				
		if (file_exists($file)) { 
			include_once($file);

			$this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
		} else {
			trigger_error('Error: Could not load model ' . $model . '!');
			exit();
		}
	}
	
	public function view($template, $data = array()) {
		if (file_exists(DIR_TEMPLATE . $template)) {
			extract($data);

			ob_start();

			require(DIR_TEMPLATE . $template);

			$output = ob_get_contents();

			ob_end_clean();

			return $output;
		} else {
			trigger_error('Error: Could not load template ' . DIR_TEMPLATE . $template . '!');
			exit();
		}
	}

	public function helper($helper) {
		$file = DIR_SYSTEM . 'helper/' . $helper . '.php';

		if (file_exists($file)) {
			include_once($file);
		} else {
			trigger_error('Error: Could not load helper ' . $helper . '!');
			exit();
		}
	}
	
	public function database($driver, $hostname, $username, $password, $database) {
		$file  = DIR_SYSTEM . 'database/' . $driver . '.php';
		$class = 'Database' . preg_replace('/[^a-zA-Z0-9]/', '', $driver);

		if (file_exists($file)) {
			include_once($file);

			$this->registry->set(str_replace('/', '_', $driver), new $class($hostname, $username, $password, $database));
		} else {
			trigger_error('Error: Could not load database ' . $driver . '!');
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
