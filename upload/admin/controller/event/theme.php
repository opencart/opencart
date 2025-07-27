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
	 * model/localisation/theme/addTheme/after
	 * model/localisation/theme/editTheme/after
	 * model/localisation/theme/deleteTheme/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'theme',
			'action' => 'admin/theme',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
