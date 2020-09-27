<?php
namespace Opencart\Application\Controller\Event;
class Language extends \Opencart\System\Engine\Controller {
	// system/config/admin.php
	// system/config/catalog.php
	// Dump all the language vars into the template.
	public function index(&$route, &$args) {
		foreach ($this->language->all() as $key => $value) {
			if (!isset($args[$key])) {
				$args[$key] = $value;
			}
		}
	}	
	
	// system/config/admin.php
	// system/config/catalog.php
	// 1. Before controller load store all current loaded language data.
	public function before(&$route, &$output) {
		$this->language->set('backup', $this->language->all());
	}
	
	// system/config/admin.php
	// system/config/catalog.php
	// 2. After controller load restore old language data.
	public function after(&$route, &$args, &$output) {
		$data = $this->language->get('backup');
		
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$this->language->set($key, $value);
			}
		}
	}
}
