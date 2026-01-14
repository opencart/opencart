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
	 * Called using admin/model/catalog/category/addCategory/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function addCategory(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'category.list',
			'action' => 'task/catalog/category.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'category.info.' . $output,
			'action' => 'task/catalog/category.info',
			'args'   => ['category_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/*
	 * Edit Category
	 *
	 * Adds task to generate new category data.
	 *
	 * Called using admin/model/catalog/category/editCategory/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function editCategory(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'category.info.' . $args[0],
			'action' => 'task/catalog/category.info',
			'args'   => ['category_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/*
	 * Delete Category
	 *
	 * Adds task to generate new category data.
	 *
	 * Called using admin/model/catalog/category/deleteCategory/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function deleteCategory(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'category.delete.' . $args[0],
			'action' => 'task/catalog/category.delete',
			'args'   => ['category_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
