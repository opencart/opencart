<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Filter
 *
 * @package Opencart\Admin\Controller\Event
 */
class Filter extends \Opencart\System\Engine\Controller {
	/*
	 * Add Filter Group
	 *
	 * Adds task to generate new filter data.
	 *
	 * Trigger admin/model/catalog/filter_group.editFilterGroup/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function addFilter(string &$route, array &$args, &$output): void {
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'filter.' . $store_id . '.' . $output,
				'action' => 'task/catalog/filter',
				'args'   => [
					'filter_group_id' => $output,
					'store_id'        => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/*
	 * Edit Filter
	 *
	 * Adds task to generate new filter data.
	 *
	 * Trigger admin/model/catalog/filter_group.editFilterGroup/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function editFilter(string &$route, array &$args, &$output): void {
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'filter.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/filter',
				'args'   => [
					'filter_group_id' => $args[0],
					'store_id'        => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/*
	 * Delete Filter
	 *
	 * Adds task to generate new filter data.
	 *
	 * Trigger admin/model/catalog/filter_group.deleteFilterGroup/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function deleteFilter(string &$route, array &$args, &$output): void {
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'filter.delete.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/filter.delete',
				'args'   => [
					'filter_group_id' => $args[0],
					'store_id'        => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}
