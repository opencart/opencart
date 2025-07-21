<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Product
 *
 * @package Opencart\Admin\Controller\Event
 */
class Product extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new product list
	 *
	 * Called using admin/model/catalog/product/addProduct/after
	 * Called using admin/model/catalog/product/editProduct/after
	 * Called using admin/model/catalog/product/deleteProduct/after
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
