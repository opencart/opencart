<?php
namespace Opencart\Application\Controller\Event;
class Language extends \Opencart\System\Engine\Controller {
	// view/*/before
	// Dump all the language vars into the template.
	public function index(&$route, &$args) {
		foreach ($this->language->all() as $key => $value) {
			if (!isset($args[$key])) {
				$args[$key] = $value;
			}
		}
	}

	// controller/*/before
	// 1. Before controller load store all current loaded language data.
	public function before(&$route, &$output) {
		$data = $this->language->all();

		if ($data) {
			$this->language->set('backup', $data);
		}
	}

	// controller/*/after
	// 2. After controller load restore old language data.
	public function after(&$route, &$args, &$output) {
		$data = $this->language->get('backup');

		if (is_array($data)) {
			$this->language->clear();

			foreach ($data as $key => $value) {
				$this->language->set($key, $value);
			}
		}
	}
}