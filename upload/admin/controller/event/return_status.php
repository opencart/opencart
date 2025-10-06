<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Return Status
 *
 * @package Opencart\Admin\Controller\Event
 */
class ReturnStatus extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new return status list
	 *
	 * model/localisation/return_status/addReturnStatus/after
	 * model/localisation/return_status/editReturnStatus/after
	 * model/localisation/return_status/deleteReturnStatus/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'return_status',
			'action' => 'task/admin/return_status',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
