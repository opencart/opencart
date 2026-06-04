<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Currency
 *
 * @package Opencart\Admin\Controller\Event
 */
class Currency extends \Opencart\System\Engine\Controller {
	/*
	 * Index
	 *
	 * Adds task to generate new currency data.
	 *
	 * Triggered using admin/model/localisation/currency/addCategory/after
	 * Triggered using admin/model/localisation/currency/editCategory/after
	 * Triggered using admin/model/localisation/currency/deleteCategory/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');
		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'currency.' . $store_id,
				'action' => 'task/catalog/currency',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);
		}

		$task_data = [
			'code'   => 'admin.currency',
			'action' => 'task/admin/currency',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);
	}
}