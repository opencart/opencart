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
		$this->load->model('setting/task');

		$store_ids = [];

		if (isset($args[1]['product_store'])) {
			$store_ids = (array)$args[1]['product_store'];
		}

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'product.' . $store_id . '.' . $output,
				'action' => 'task/catalog/product',
				'args'   => [
					'product_id' => $output,
					'store_id'   => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);

			// Categories
			$category_ids = [];

			if (isset($args[1]['product_category'])) {
				$category_ids = (array)$args[1]['product_category'];
			}

			foreach ($category_ids as $category_id) {
				$task_data = [
					'code'   => 'category.product.' . $store_id . '.' . $category_id,
					'action' => 'task/catalog/category.product',
					'args'   => [
						'category_id' => $category_id,
						'store_id'    => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}

			// Manufacturer
			$task_data = [
				'code'   => 'manufacturer.product.' . $store_id . '.' . $args[1]['manufacturer_id'],
				'action' => 'task/catalog/manufacturer.product',
				'args'   => [
					'manufacturer_id' => $args[1]['manufacturer_id'],
					'store_id'        => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);

			// Filters
			$filter_ids = [];

			if (isset($args[1]['product_filter'])) {
				$filter_ids = (array)$args[1]['product_filter'];
			}

			foreach ($filter_ids as $filter_id) {
				$task_data = [
					'code'   => 'filter.product.' . $store_id . '.' . $filter_id,
					'action' => 'task/catalog/filter.product',
					'args'   => [
						'filter_id' => $filter_id,
						'store_id'  => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
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
		$this->load->model('setting/task');

		$store_ids = [];

		if (isset($args[1]['product_store'])) {
			$store_ids = (array)$args[1]['product_store'];
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($args[0]);

		// Categories
		$category_ids = [];

		if (isset($args[1]['product_category'])) {
			$category_ids = (array)$args[1]['product_category'];
		}

		$category_ids = array_unique(array_merge($this->model_catalog_product->getCategories($args[0]), $category_ids));

		// Filters
		$filter_ids = [];

		if (isset($args[1]['product_filter'])) {
			$filter_ids = (array)$args[1]['product_filter'];
		}

		$filter_ids = array_unique(array_merge($this->model_catalog_product->getFilters($args[0]), $filter_ids));

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'product.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/product',
				'args'   => [
					'product_id' => $args[0],
				    'store_id'   => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);

			foreach ($category_ids as $category_id) {
				$task_data = [
					'code'   => 'category.product.' . $store_id . '.' . $category_id,
					'action' => 'task/catalog/category.product',
					'args'   => [
						'category_id' => $category_id,
						'store_id'    => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}

			// Manufacturer
			$task_data = [
				'code'   => 'manufacturer.product.' . $store_id . '.' . $args[1]['manufacturer_id'],
				'action' => 'task/catalog/manufacturer.product',
				'args'   => [
					'manufacturer_id' => $args[1]['manufacturer_id'],
					'store_id'        => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);

			if ($product_info && $product_info['manufacturer_id'] != $args[1]['manufacturer_id']) {
				// Manufacturer
				$task_data = [
					'code'   => 'manufacturer.product.' . $store_id . '.' . $product_info['manufacturer_id'],
					'action' => 'task/catalog/manufacturer.product',
					'args'   => [
						'manufacturer_id' => $product_info['manufacturer_id'],
						'store_id'        => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}

			// Filters
			foreach ($filter_ids as $filter_id) {
				$task_data = [
					'code'   => 'filter.product.' . $store_id . '.' . $filter_id,
					'action' => 'task/catalog/filter.product',
					'args'   => [
						'filter_id' => $filter_id,
						'store_id'  => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		// Remove from stores
		$remove_ids = array_diff($this->model_catalog_product->getStores($args[0]), $store_ids);

		foreach ($remove_ids as $remove_id) {
			$task_data = [
				'code'   => 'product.delete.' . $remove_id . '.' . $args[0],
				'action' => 'task/catalog/product.delete',
				'args'   => [
					'product_id' => $args[0],
					'store_id'   => $remove_id
				]
			];

			$this->model_setting_task->addTask($task_data);

			foreach ($category_ids as $category_id) {
				$task_data = [
					'code'   => 'category.product.' . $remove_id . '.' . $category_id,
					'action' => 'task/catalog/category.product',
					'args'   => [
						'category_id' => $category_id,
						'store_id'    => $remove_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}

			// Manufacturer
			$task_data = [
				'code'   => 'manufacturer.product.' . $remove_id . '.' . $args[1]['manufacturer_id'],
				'action' => 'task/catalog/manufacturer.product',
				'args'   => [
					'manufacturer_id' => $args[1]['manufacturer_id'],
					'store_id'        => $remove_id
				]
			];

			$this->model_setting_task->addTask($task_data);

			if ($product_info && $product_info['manufacturer_id'] != $args[1]['manufacturer_id']) {
				// Manufacturer
				$task_data = [
					'code'   => 'manufacturer.product.' . $remove_id . '.' . $product_info['manufacturer_id'],
					'action' => 'task/catalog/manufacturer.product',
					'args'   => [
						'manufacturer_id' => $product_info['manufacturer_id'],
						'store_id'        => $remove_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}

			// Filters
			foreach ($filter_ids as $filter_id) {
				$task_data = [
					'code'   => 'filter.product.' . $remove_id . '.' . $filter_id,
					'action' => 'task/catalog/filter.product',
					'args'   => [
						'filter_id' => $filter_id,
						'store_id'  => $remove_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
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
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($args[0]);

		$store_ids = $this->model_catalog_product->getStores($args[0]);

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'product.delete.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/product.delete',
				'args'   => [
					'product_id' => $args[0],
					'store_id'   => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);






		}
	}
}
