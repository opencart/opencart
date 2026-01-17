<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Product
 *
 * @package Opencart\Admin\Controller\Event
 */
class Product extends \Opencart\System\Engine\Controller {
	/**
	 * Add Product
	 *
	 * Adds task to generate new product data.
	 *
	 * Called using model/catalog/product/addProduct/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function addProduct(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'product.info.' . $output,
			'action' => 'task/catalog/product.info',
			'args'   => ['product_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Edit Product
	 *
	 * Adds task to generate new product data.
	 *
	 * Called using model/catalog/product/editProduct/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function editProduct(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'product.info.' . $args[0],
			'action' => 'task/catalog/product.info',
			'args'   => ['product_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Delete Product
	 *
	 * Adds task to generate new product data.
	 *
	 * Called using model/catalog/product/deleteProduct/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function deleteProduct(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'product.delete.' . $args[0],
			'action' => 'task/catalog/product.delete',
			'args'   => ['product_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
