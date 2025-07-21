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
	 * model/localisation/length_class/addLengthClass
	 * model/localisation/length_class/editLengthClass
	 * model/localisation/length_class/deleteLengthClass
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
			'code'   => 'length_class',
			'action' => 'catalog/cli/data/length_class',
			'args'   => []
		];

		$tasks[] = [
			'code'   => 'length_class',
			'action' => 'admin/cli/data/length_class',
			'args'   => []
		];

		$this->load->model('setting/task');

		foreach ($tasks as $task) {
			$this->model_setting_task->addTask($task);
		}
	}
}
