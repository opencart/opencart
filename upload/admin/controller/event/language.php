<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Language
 *
 * @package Opencart\Admin\Controller\Event
 */
class Language extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new language list
	 *
	 * Called using admin/model/localisation/language/addLanguage/after
	 * Called using admin/model/localisation/language/editLanguage/after
	 * Called using admin/model/localisation/language/deleteLanguage/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'language',
			'action' => 'task/catalog/language',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'language',
			'action' => 'task/admin/language',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * template
	 *
	 * Dump all the language vars into the template.
	 *
	 * view/ * /before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function template(string &$route, array &$args): void {
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
	 * controller/ * /before
	 *
	 * @param string       $route
	 * @param array<mixed> $args
	 *
	 * @return void
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
	 * controller/ * / * /after
	 *
	 * @param string       $route
	 * @param array<mixed> $args
	 * @param mixed        $output
	 *
	 * @return void
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
