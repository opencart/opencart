<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Translation
 *
 * @package Opencart\Admin\Controller\Event
 */
class Translation extends \Opencart\System\Engine\Controller {
	/**
	 * Add Translation
	 *
	 * Adds task to generate new translation data
	 *
	 * Called using admin/model/design/translation/addTranslation/after
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

	/**
	 * Edit Translation
	 *
	 * Adds task to generate new translation data
	 *
	 * Called using admin/model/design/translation/editTranslation/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function editTranslation(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'translation.info.' . str_replace('/', '.', $args[0]['route']),
			'action' => 'task/catalog/translation.info',
			'args'   => ['route' => $args[0]['route']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Delete Translation
	 *
	 * Adds task to generate new translation data
	 *
	 * Called using admin/model/design/translation/deleteTranslation/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function deleteTranslation(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'translation.delete.' . str_replace('/', '.', $args[0]['route']),
			'action' => 'task/catalog/translation.delete',
			'args'   => ['route' => $args[0]['route']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
