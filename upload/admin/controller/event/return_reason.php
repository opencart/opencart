<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Statistics
 *
 * @package Opencart\Admin\Controller\Event
 */
class ReturnReason extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new return_reason list
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
		$tasks = [];

		$tasks[] = [
			'code'   => 'return_reason',
			'action' => 'catalog/cli/data/return_reason',
			'args'   => []
		];

		$tasks[] = [
			'code'   => 'return_reason',
			'action' => 'admin/cli/data/return_reason',
			'args'   => []
		];

		$this->load->model('setting/task');

		foreach ($tasks as $task) {
			$this->model_setting_task->addTask($task);
		}
	}
}
