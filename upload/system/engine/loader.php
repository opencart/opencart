<?php
final class Loader {
	private $registry;
	private $hooks = array();

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function addHook($path, $object) {
		$this->hooks[$type][] = $object;
	}

  	public function __load() {


  	}

	public function controller($route, $args = array()) {
		$action = new Action($route, $args);

		return $action->execute($this->registry);
	}

	public function model($model) {
		$file = DIR_APPLICATION . 'model/' . $model . '.php';
/*
		if (is_file($file)) {
			include_once($file);

			$this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
		} else {
			$extension = end($parts);

			$file = DIR_EXTENSION . $extension[0] . '/model/' . $extension[1] . '.php';

			if (is_file($file)) {
				include_once($file);
			}
		}
*/		
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
		/*
		if (isset($this->hooks[$type])) {
			foreach ($this->hook as $hook) {
				$hook->call(&$template, &data);
			}
		}
		*/
		$file = DIR_TEMPLATE . $template;

		if (!file_exists($file)) {
			trigger_error('Error: Could not load template ' . $file . '!');
			exit();
		}

		if (isset($data['url']) || isset($data['lng']) || isset($data['load'])) {
			trigger_error('Error: you must not override url, lng, load and request parameters in the $data array!');
			exit();
		}

		$data['url'] = $this->registry->get('url');
		$data['lng'] = $this->registry->get('language');
		$data['load'] = $this->registry->get('load');
		$data['request'] = $this->registry->get('request');

		extract($data);
		ob_start();
		require($file);
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
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

	public function config($config) {
		$this->registry->get('config')->load($config);
	}

	public function language($language) {
		return $this->registry->get('language')->load($language);
	}
}
