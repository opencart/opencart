<?php
namespace Opencart\Catalog\Controller\Event;
/**
 * Class Language
 *
 * @package Opencart\Catalog\Controller\Event
 */
class Language extends \Opencart\System\Engine\Controller {
	// view/*/before
	// Dump all the language vars into the template.
	/**
	 * @param string $route
	 * @param array  $args
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args): void {
		foreach ($this->language->all() as $key => $value) {
			if (!isset($args[$key])) {
				$args[$key] = $value;
			}
		}
	}

	// controller/*/before
	// 1. Before controller load store all current loaded language data
	/**
	 * @param string $route
	 * @param array  $args
	 *
	 * @return void
	 */
	public function before(string &$route, array &$args): void {
		$data = $this->language->all();

		if ($data) {
			$this->language->set('backup', json_encode($data));
		}
	}

	// controller/*/after
	// 2. After controller load restore old language data
	/**
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 */
	public function after(string &$route, array &$args, mixed &$output): void {
		$data = json_decode($this->language->get('backup'), true);

		if (is_array($data)) {
			$this->language->clear();

			foreach ($data as $key => $value) {
				$this->language->set($key, $value);
			}
		}
	}
}