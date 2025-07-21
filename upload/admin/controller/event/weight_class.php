<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Weight Class
 *
 * @package Opencart\Admin\Controller\Event
 */
class WeightClass extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new weight_class list
	 *
	 * model/localisation/weight_class/addWeightClass
	 * model/localisation/weight_class/editWeightClass
	 * model/localisation/weight_class/deleteWeightClass
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
			'code'   => 'weight_class',
			'action' => 'catalog/cli/data/weight_class',
			'args'   => []
		];

		$tasks[] = [
			'code'   => 'weight_class',
			'action' => 'admin/cli/data/weight_class',
			'args'   => []
		];

		$this->load->model('setting/task');

		foreach ($tasks as $task) {
			$this->model_setting_task->addTask($task);
		}
	}
}
