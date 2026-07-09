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
		$this->load->model('setting/task');

		$this->load->model('catalog/filter');

		$filters = $this->model_catalog_filter->getFilters((int)$args['filter_group_id']);

		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'filter.info.' . $store_id . '.' . $output,
				'action' => 'task/catalog/filter.info',
				'args'   => [
					'filter_group_id' => $output,
					'store_id'        => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);

			// Added filter product tasks
			foreach ($filters as $filter) {
				$task_data = [
					'code'   => 'filter.product.' . $store_id . '.' . $filter['filter_id'],
					'action' => 'task/catalog/filter.product',
					'args'   => [
						'filter_id' => $filter['filter_id'],
						'store_id'  => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
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
	public function editFilter(string &$route, array &$args): void {
		$this->load->model('setting/task');

		// Filters
		$filter_ids = [];

		if (isset($args[1]['filter'])) {
			$filter_ids = array_column((array)$args[1]['filter'], 'filter_id');
		}

		$this->load->model('catalog/filter');

		$remove_ids = array_diff(array_column($this->model_catalog_filter->getFilters(['filter_group_id' => $args[0]]), 'filter_id'), $filter_ids);

		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'filter.info.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/filter.info',
				'args'   => [
					'filter_group_id' => $args[0],
					'store_id'        => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);

			foreach ($remove_ids as $remove_id) {
				$task_data = [
					'code'   => 'filter.deleteFilter.' . $store_id . '.' . $remove_id,
					'action' => 'task/catalog/filter.deleteFilter',
					'args'   => [
						'filter_id' => $remove_id,
						'store_id'  => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
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
		$this->load->model('setting/task');

		$this->load->model('catalog/filter');

		$filters = $this->model_catalog_filter->getFilters((int)$args['filter_group_id']);

		$this->load->model('setting/store');

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

			foreach ($filters as $filter) {
				$task_data = [
					'code'   => 'filter.deleteFilter.' . $store_id . '.' . $filter['filter_id'],
					'action' => 'task/catalog/filter.deleteFilter',
					'args'   => [
						'filter_id' => $filter['filter_id'],
						'store_id'  => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}
	}
}
