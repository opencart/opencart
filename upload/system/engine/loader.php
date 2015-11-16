<?php
final class Loader {
	private $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function controller($route, $data = array()) {
		//$this->registry->get('event')->trigger('controller/' . $route . '/before', $route, $data);

		$parts = explode('/', str_replace('../', '', (string)$route));

		// Break apart the route
		while ($parts) {
			$file = DIR_APPLICATION . 'controller/' . implode('/', $parts) . '.php';
			$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', implode('/', $parts));

			if (is_file($file)) {
				include_once($file);

				break;
			} else {
				$method = array_pop($parts);
			}
		}

		if (!isset($method)) {
			$method = 'index';
		}

		// Stop any magical methods being called
		if (substr($method, 0, 2) == '__') {
			return false;
		}

		$controller = new $class($this->registry);

		if (is_callable(array($controller, $method))) {
			$output = call_user_func(array($controller, $method), $data);
		} else {
			$output = null;
		}

		//$this->registry->get('event')->trigger('controller/' . $route . '/after', $output);
		
		return $output;
	}

	public function model($model, $data = array()) {
		//$this->registry->get('event')->trigger('model/' . $model . '/before', $model, $data);

		$model = str_replace('../', '', (string)$model);

		$file = DIR_APPLICATION . 'model/' . $model . '.php';
		$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);

		if (file_exists($file)) {
			include_once($file);
			
			$object = new $class($this->registry);
			
			//$aspect = new Aspect($this->registry);
			//$test = $aspect->factory($object);
			
			//$aspect->addListener('addOrderHistory', $this->registry->get('event'));

			$this->registry->set('model_' . str_replace('/', '_', $model), $object);
		} else {
			trigger_error('Error: Could not load model ' . $file . '!');
			exit();
		}

		//$this->registry->get('event')->trigger('model/' . $model . '/after', $output);
	}

	public function view($view, $data = array()) {
		//$this->registry->get('event')->trigger('view/' . $view . '/before', $view, $data);

		$template = new Template('basic');
		
		foreach ($data as $key => $value) {
			$template->set($key, $value);
		}
		
		$output = $template->render($view);	

		//$this->registry->get('event')->trigger('view/' . $view . '/after', $output);

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
		//$this->registry->get('event')->trigger('config/' . $config . '/before', $config);
		
		$this->registry->get('config')->load($config);
		
		//$this->registry->get('event')->trigger('config/' . $config . '/after');
	}

	public function language($language) {
		//$this->registry->get('event')->trigger('language/' . $language . '/before', $language);
		
		$this->registry->get('language')->load($language);
		
		//$this->registry->get('event')->trigger('language/' . $language . '/after');
	}
}
