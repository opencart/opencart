<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Category
 *
 * @package Opencart\Admin\Controller\Event
 */
class Category extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new category list
	 *
	 * Called using admin/model/catalog/category/addCategory/after
	 * Called using admin/model/catalog/category/editCategory/after
	 * Called using admin/model/catalog/category/deleteCategory/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args): void {
		$tasks = [];

		$tasks[] = [
			'code'   => 'category',
			'action' => 'catalog/cli/data/category',
			'args'   => []
		];

		$tasks[] = [
			'code'   => 'product',
			'action' => 'catalog/cli/data/product',
			'args'   => []
		];

		$this->load->model('setting/task');

		foreach ($tasks as $task) {
			$this->model_setting_task->addTask($task);
		}
	}
}
