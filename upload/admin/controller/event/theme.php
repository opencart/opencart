<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Theme
 *
 * @package Opencart\Admin\Controller\Event
 */
class Theme extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new theme list
	 *
	 * model/design/theme/addTheme/after
	 * model/design/theme/editTheme/after
	 * model/design/theme/deleteTheme/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function addTheme(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'theme.info.' . str_replace('/', '.', $args[0]['route']),
			'action' => 'task/catalog/theme.info',
			'args'   => ['route' => $args[0]['route']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function editTheme(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'theme.info.' . str_replace('/', '.', $args[0]['route']),
			'action' => 'task/catalog/theme.info',
			'args'   => ['route' => $args[0]['route']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function deleteTheme(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'theme.delete.' . str_replace('/', '.', $args[0]['route']),
			'action' => 'task/catalog/theme.delete',
			'args'   => ['route' => $args[0]['route']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
