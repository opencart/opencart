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

		$store_ids = [];

		if (isset($args[1]['category_store'])) {
			$store_ids = (array)$args[1]['category_store'];
		}

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

		$store_ids = [];

		if (isset($args[1]['category_store'])) {
			$store_ids = (array)$args[1]['category_store'];
		}

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'category.' . $store_id,
				'action' => 'task/catalog/category',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'category.info.' . $store_id . '.' . $args[0],
				'action' => 'task/ssr/category.info',
				'args'   => [
					'category_id' => $args[0],
					'store_id'    => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Remove from stores
		$this->load->model('catalog/category');

		$remove_ids = array_diff($this->model_catalog_category->getStores($args[0]), $store_ids);

		foreach ($remove_ids as $remove_id) {
			$task_data = [
				'code'   => 'category.delete.' . $remove_id . '.' . $args[0],
				'action' => 'task/ssr/category.delete',
				'args'   => [
					'category_id' => $args[0],
					'store_id'    => $remove_id
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

		$this->load->model('catalog/category');

		$store_ids = $this->model_catalog_category->getStores($args[0]);

		foreach ($store_ids as $store_id) {
			// Refresh List
			$task_data = [
				'code'   => 'category.' . $store_id,
				'action' => 'task/ssr/category',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			// Delete
			$task_data = [
				'code'   => 'category.delete.' . $store_id . '.' . $args[0],
				'action' => 'task/ssr/category.delete',
				'args'   => [
					'category_id' => $args[0],
					'store_id'    => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}
