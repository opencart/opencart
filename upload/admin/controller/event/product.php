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
	 * Trigger model/catalog/product/addProduct/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function addProduct(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'product.' . $output,
			'action' => 'task/catalog/product',
			'args'   => ['product_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Categories
		$category_ids = $args[1]['product_category'];

		foreach ($category_ids as $category_id) {
			$task_data = [
				'code'   => 'category.product.' . $category_id,
				'action' => 'task/catalog/category.product',
				'args'   => ['category_id' => $category_id]
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Manufacturer
		$task_data = [
			'code'   => 'manufacturer.product.' . $args[1]['manufacturer_id'],
			'action' => 'task/catalog/manufacturer.product',
			'args'   => ['manufacturer_id' => $args[1]['manufacturer_id']]
		];

		$this->model_setting_task->addTask($task_data);

		// Filters
		$filter_ids = $args[1]['product_filter'];

		foreach ($filter_ids as $filter_id) {
			$task_data = [
				'code'   => 'filter.product.' . $filter_id,
				'action' => 'task/catalog/filter.product',
				'args'   => ['filter_id' => $filter_id]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/**
	 * Edit Product
	 *
	 * Adds task to generate new product data.
	 *
	 * Trigger model/catalog/product/editProduct/before
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function editProduct(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'product.' . $args[0],
			'action' => 'task/catalog/product',
			'args'   => ['product_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Categories
		$this->load->model('catalog/filter');

		$category_ids = array_unique(array_merge($args[1]['product_category'], $this->model_catalog_product->getCategories($args[0])));

		foreach ($category_ids as $category_id) {
			$task_data = [
				'code'   => 'category.product.' . $category_id,
				'action' => 'task/catalog/category.product',
				'args'   => ['category_id' => $category_id]
			];

			$this->model_setting_task->addTask($task_data);
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($args[0]);

		$manufacturer_ids = array_unique([$args[1]['manufacturer_id'], $product_info['manufacturer_id']]);

		foreach ($manufacturer_ids as $manufacturer_id) {
			// Manufacturer
			$task_data = [
				'code'   => 'manufacturer.product.' . $manufacturer_id,
				'action' => 'task/catalog/manufacturer.product',
				'args'   => ['manufacturer_id' => $manufacturer_id]
			];
S
			$this->model_setting_task->addTask($task_data);
		}

		// Filters
		$this->load->model('catalog/filter');

		$filter_ids = array_unique(array_merge($args[1]['product_filter'], $this->model_catalog_product->getFilters($args[0])));

		foreach ($filter_ids as $filter_id) {
			$task_data = [
				'code'   => 'filter.product.' . $filter_id,
				'action' => 'task/catalog/filter.product',
				'args'   => ['filter_id' => $filter_id]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/**
	 * Delete Product
	 *
	 * Adds task to generate new product data.
	 *
	 * Trigger model/catalog/product/deleteProduct/before
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
