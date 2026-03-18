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
	 * Adds task to generate new product data.
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
		$task_data = [
			'code'   => 'filter_group.' . $output,
			'action' => 'task/catalog/filter',
			'args'   => ['filter_group_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/*
	 * Edit Filter
	 *
	 * Adds task to generate new product data.
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
		$task_data = [
			'code'   => 'filter_group.' . $args[0],
			'action' => 'task/catalog/filter',
			'args'   => ['filter_group_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Update Categories
		$this->load->model('catalog/category');

		$results = $this->model_catalog_category->getCategoriesByFilterId($args[0]);

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'product.' . $result['product_id'],
				'action' => 'task/catalog/product',
				'args'   => ['product_id' => $result['product_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Update products
		$this->load->model('catalog/product');

		$results = $this->model_catalog_product->getProductsByFilterId($args[0]);

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'product' . $result['product_id'],
				'action' => 'task/catalog/product',
				'args'   => ['product_id' => $result['product_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/*
	 * Delete Filter
	 *
	 * Adds task to generate new product data.
	 *
	 * Trigger admin/model/catalog/filter.deleteFilter/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function deleteFilter(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'filter.' . $args[0],
			'action' => 'task/catalog/filter',
			'args'   => ['filter_id' => $args[0]],
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$this->load->model('catalog/product');

		$results = $this->model_catalog_product->getProductsByFilterId($args[0]);

		$this->load->model('setting/task');

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'product.' . $result['product_id'],
				'action' => 'task/catalog/product',
				'args'   => ['product_id' => $result['product_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}
