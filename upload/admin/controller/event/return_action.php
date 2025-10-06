<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Return Action
 *
 * @package Opencart\Admin\Controller\Event
 */
class ReturnAction extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new return action list
	 *
	 * model/localisation/return_action/addReturnAction/after
	 * model/localisation/return_action/editReturnAction/after
	 * model/localisation/return_action/deleteReturnAction/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'return_action',
			'action' => 'task/admin/return_action',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
