<?php
namespace Opencart\admin\controller\ssr\design;
/**
 * Class Translation
 *
 * @package Opencart\Admin\Controller\Event
 */
class Translation extends \Opencart\System\Engine\Controller {
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
