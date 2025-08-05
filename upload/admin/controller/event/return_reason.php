<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Return Reason
 *
 * @package Opencart\Admin\Controller\Event
 */
class ReturnReason extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new return reason list
	 *
	 * model/localisation/return_reason/addReturnReason/after
	 * model/localisation/return_reason/editReturnReason/after
	 * model/localisation/return_reason/deleteReturnReason/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'return_reason',
			'action' => 'task/catalog/return_reason',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'return_reason',
			'action' => 'task/admin/return_reason',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);
	}
}
