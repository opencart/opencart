<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Language
 *
 * @package Opencart\Admin\Controller\Event
 */
class Language extends \Opencart\System\Engine\Controller {

	/**
	 * Dump all the language vars into the template.
	 *
	 * @param string $route
	 * @param array  $args
	 *
	 * @return void
	 *
	 * view/ * /before
	 */
	public function index(string &$route, array &$args): void {
		foreach ($this->language->all() as $key => $value) {
			if (!isset($args[$key])) {
				$args[$key] = $value;
			}
		}
	}

	/**
	 * Before
	 *
	 * 1. Before controller load store all current loaded language data.
	 *
	 * @param string $route
	 * @param array  $args
	 *
	 * @return void
	 *
	 * controller/ * /before
	 */
	public function before(string &$route, array &$args): void {
		$data = $this->language->all();

		if ($data) {
			$this->language->set('backup', json_encode($data));
		}
	}

	/**
	 * After
	 *
	 * 2. After controller load restore old language data.
	 *
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 *
	 * controller/ * / * /after
	 */
	public function after(string &$route, array &$args, &$output): void {
		$data = json_decode($this->language->get('backup'), true);

		if (is_array($data)) {
			$this->language->clear();

			foreach ($data as $key => $value) {
				$this->language->set($key, $value);
			}
		}
	}
}
