<?php
class Template {
	private $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function render($filename, $data) {
		if (file_exists(DIR_TEMPLATE . $this->registry->config->get('config_template') . '/template/common/' . $filename)) {
			$this->registry->response->setOutput($this->registry->load->view($this->registry->config->get('config_template') . '/template/common/' . $filename, $data));
		} else {
			$this->registry->response->setOutput($this->registry->load->view('default/template/common/' . $filename, $data));
		}
	}

/*
	private $data = array();

  public function __construct($driver) {
	    $class = 'Template\\' . $driver;

		if (class_exists($class)) {
			$this->template = new $class($expire);
		} else {
			exit('Error: Could not load template driver ' . $driver . ' cache!');
		}
	}

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function render() {
		$file = DIR_TEMPLATE . $template;

		if (file_exists($file)) {
			extract($data);

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
*/
}
