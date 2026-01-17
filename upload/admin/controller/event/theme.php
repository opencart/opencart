<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Theme
 *
 * @package Opencart\Admin\Controller\Event
 */
class Theme extends \Opencart\System\Engine\Controller {
	/**
	 * Add Theme
	 *
	 * Adds task to generate new theme data.
	 *
	 * model/design/theme/addTheme/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function addTheme(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'theme.info.' . $output,
			'action' => 'task/catalog/theme.info',
			'args'   => ['theme_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Edit Theme
	 *
	 * Adds task to generate new theme data
	 *.
	 * model/design/theme/editTheme/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function editTheme(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'theme.info.' . $args[0],
			'action' => 'task/catalog/theme.info',
			'args'   => ['theme_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Delete Theme
	 *
	 * Adds task to generate new theme data.
	 *
	 * model/design/theme/deleteTheme/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function deleteTheme(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'theme.delete.' . $args[0],
			'action' => 'task/catalog/theme.delete',
			'args'   => ['theme_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
