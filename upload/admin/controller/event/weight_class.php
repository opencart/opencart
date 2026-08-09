<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Weight Class
 *
 * @package Opencart\Admin\Controller\Event
 */
class WeightClass extends \Opencart\System\Engine\Controller {
	/*
	 * Index
	 *
	 * Adds task to generate new weight class data.
	 *
	 * Triggered using admin/model/localisation/weight_class/addWeightClass/after
	 * Triggered using admin/model/localisation/weight_class/editWeightClass/after
	 * Triggered using admin/model/localisation/weight_class/deleteWeightClass/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args): void {
		$this->load->model('setting/task');

		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'weight_class.' . $store_id,
				'action' => 'task/catalog/weight_class',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}