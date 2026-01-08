<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Translation
 *
 * @package Opencart\Admin\Controller\Event
 */
class Translation extends \Opencart\System\Engine\Controller {
	public function index(string &$route, array &$args, &$output): void {
		$pos = strpos($route, '.');

		if ($pos == false) {
			return;
		}

		$method = substr($route, 0, $pos);

		$callable = [$this, $method];

		if (is_callable($callable)) {
			$callable($route, $args, $output);
		}
	}

	/**
	 * Index
	 *
	 * Adds task to generate new translation list
	 *
	 * Called using admin/model/localisation/country/addTranslation/after
	 * Called using admin/model/localisation/country/editTranslation/after
	 * Called using admin/model/localisation/country/deleteTranslation/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function addTranslation(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'translation.info.' . str_replace('/', '.', $args[0]['route']),
			'action' => 'task/catalog/translation.info',
			'args'   => ['route' => $args[0]['route']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function editTranslation(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'translation.info.' . str_replace('/', '.', $args[0]['route']),
			'action' => 'task/catalog/translation.info',
			'args'   => ['route' => $args[0]['route']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function deleteTranslation(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'translation.info.' . str_replace('/', '.', $args[0]['route']),
			'action' => 'task/catalog/translation.info',
			'args'   => ['route' => $args[0]['route']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
