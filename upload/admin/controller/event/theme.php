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
	 * model/localisation/weight_class/addTheme
	 * model/localisation/weight_class/editTheme
	 * model/localisation/weight_class/deleteTheme
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$tasks = [];

		$tasks[] = [
			'code'   => 'theme',
			'action' => 'admin/data/theme',
			'args'   => []
		];

		$this->load->model('setting/task');

		foreach ($tasks as $task) {
			$this->model_setting_task->addTask($task);
		}
	}
}
