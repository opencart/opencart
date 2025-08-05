<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Length Class
 *
 * @package Opencart\Admin\Controller\Event
 */
class LengthClass extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new length_class list
	 *
	 * model/localisation/length_class/addLengthClass/after
	 * model/localisation/length_class/editLengthClass/after
	 * model/localisation/length_class/deleteLengthClass/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'length_class',
			'action' => 'task/admin/length_class',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
