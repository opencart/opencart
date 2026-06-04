<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Category
 *
 * @package Opencart\Admin\Controller\Event
 */
class Category extends \Opencart\System\Engine\Controller {
	/*
	 * Add Category
	 *
	 * Adds task to generate new category data.
	 *
	 * Trigger admin/model/catalog/category/addCategory/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function addCategory(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');
		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'category.' . $store_id,
				'action' => 'task/catalog/category',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'category.info.' . $store_id . '.' . $output,
				'action' => 'task/catalog/category.info',
				'args'   => [
					'category_id' => $output,
					'store_id'    => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/*
	 * Edit Category
	 *
	 * Adds task to generate new category data.
	 *
	 * Trigger admin/model/catalog/category/editCategory/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function editCategory(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');
		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'category.' . $store_id,
				'action' => 'task/catalog/category',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'category.info.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/category.info',
				'args'   => [
					'category_id' => $args[0],
					'store_id'    => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/*
	 * Delete Category
	 *
	 * Adds task to generate new category data.
	 *
	 * Trigger admin/model/catalog/category/deleteCategory/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function deleteCategory(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');
		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'category.' . $store_id,
				'action' => 'task/catalog/category',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'category.delete.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/category.delete',
				'args'   => [
					'category_id' => $args[0],
					'store_id'    => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}
