<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Option
 *
 * @package Opencart\Admin\Controller\Event
 */
class Option extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new option list
	 *
	 * model/catalog/option/addOption/after
	 * model/catalog/option/editOption/after
	 * model/catalog/option/deleteOption/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'option',
			'action' => 'task/catalog/option',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'option',
			'action' => 'task/admin/option',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);
	}
}
