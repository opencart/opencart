<?php
class ControllerStartupAutoload extends Controller {
	public function index() {
		/*
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "autoload`");

		// Config Autoload
		foreach ($query->rows as $result) {
			$this->loader->config($result['catalog/']);
		}

		// Language Autoload
		if ($config->has('language_autoload')) {
			foreach ($config->get('language_autoload') as $value) {
				$this->loader->language($value);
			}
		}

		// Library Autoload
		if ($config->has('library_autoload')) {
			foreach ($config->get('library_autoload') as $value) {
				$this->loader->library($value);
			}
		}

		// Model Autoload
		if ($config->has('model_autoload')) {
			foreach ($config->get('model_autoload') as $value) {
				$loader->model($value);
			}
		}

		// Pre Actions
		if ($config->has('action_pre_action')) {
			foreach ($config->get('action_pre_action') as $value) {
				$route->addPreAction(new Action($value));
			}
		}
		*/
	}
}
