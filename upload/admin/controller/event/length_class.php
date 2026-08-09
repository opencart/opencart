<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Length Class
 *
 * @package Opencart\Admin\Controller\Event
 */
class LengthClass extends \Opencart\System\Engine\Controller {
	/*
	 * Index
	 *
	 * Adds task to generate new length class data.
	 *
	 * Triggered using admin/model/localisation/length_class/addLengthClass/after
	 * Triggered using admin/model/localisation/length_class/editLengthClass/after
	 * Triggered using admin/model/localisation/length_class/deleteLengthClass/after
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
				'code'   => 'length_class.' . $store_id,
				'action' => 'task/catalog/length_class',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}