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
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'category',
			'action' => 'task/catalog/category',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'product',
			'action' => 'task/catalog/product',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);
	}
}
