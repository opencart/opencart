<?php
namespace Opencart\Application\Controller\Event;
class Language extends \Opencart\System\Engine\Controller {
	public function index(&$route, &$args) {
		//if () {

		//}

		$this->language_old = $this->language;

		/*
		foreach ($this->language->all() as $key => $value) {
			if (!isset($args[$key])) {
				$args[$key] = $value;
			}
		}
		*/
	}
	
	// 1. Before controller load store all current loaded language data
	public function before(&$route, &$output) {
		$this->language->set('backup', $this->language->all());
	}
	
	// 2. After controller load restore old language data
	public function after(&$route, &$args, &$output) {
		$this->language= $this->language_old;


		$data = $this->language->get('backup');
		
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$this->language->set($key, $value);
			}
		}
	}
}