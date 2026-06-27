<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Category
 *
 * Generates category information.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Category extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate category list task by store.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/category');

		if (!array_key_exists('store_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		$task_data = [
			'code'   => 'category.list.' . $store_info['store_id'],
			'action' => 'task/catalog/category.list',
			'args'   => ['store_id' => $store_info['store_id']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$filter_data = [
			'filter_store_id' => $store_info['store_id'],
			'filter_status'   => true,
			'sort'            => 'sort_order',
			'order'           => 'ASC',
		];

		$this->load->model('catalog/category');

		$results = $this->model_catalog_category->getCategories($filter_data);

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'category.info.' . $store_info['store_id'] . '.' . $result['category_id'],
				'action' => 'task/catalog/category.info',
				'args'   => [
					'category_id' => $result['category_id'],
					'store_id'    => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_task'), $store_info['name'])];
	}

	/**
	 * List
	 *
	 * Generate all category list data for all stores.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/category');

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		// Category
		$category_data = [];

		$filter_data = [
			'filter_parent_id' => 0,
			'filter_store_id'  => $store_info['store_id'],
			'filter_status'    => true,
			'sort'             => 'sort_order',
			'order'            => 'ASC',
		];

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');

		$results = $this->model_catalog_category->getCategories($filter_data);

		foreach ($results as $result) {
			$children_data = [];

			// Filter children
			$filter_data = [
				'filter_parent_id' => $result['category_id'],
				'filter_store_id'  => $store_info['store_id'],
				'filter_status'    => true,
				'sort'             => 'sort_order',
				'order'            => 'ASC',
			];

			$children = $this->model_catalog_category->getCategories($filter_data);

			foreach ($children as $child) {
				$description_data = [];

				$descriptions = $this->model_catalog_category->getDescriptions($child['category_id']);

				foreach ($descriptions as $code => $description) {
					$description_data[$code] = ['name' => $description['name']];
				}

				$filter_data = [
					'filter_category_id' => $result['category_id'],
					'filter_store_id'    => $store_info['store_id'],
					'filter_status'      => true
				];

				$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

				$children_data[] = [
					'category_id'   => $child['category_id'],
					'description'   => $description_data,
					'image'         => $child['image'],
					'path'          => $result['category_id'] . '_' . $child['category_id'],
					'product_total' => $product_total
				];
			}

			$description_data = [];

			$descriptions = $this->model_catalog_category->getDescriptions($result['category_id']);

			foreach ($descriptions as $code => $description) {
				$description_data[$code] = ['name' => $description['name']];
			}

			$filter_data = [
				'filter_category_id' => $result['category_id'],
				'filter_store_id'    => $store_info['store_id'],
				'filter_status'      => true
			];

			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			$category_data[] = [
				'category_id'   => $result['category_id'],
				'description'   => $description_data,
				'image'         => $result['image'],
				'path'          => $result['category_id'],
				'children'      => $children_data,
				'product_total' => $product_total
			];
		}

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/catalog/';
		$filename = 'category.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($category_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'])];
	}

	/**
	 * Info
	 *
	 * Generate category information.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/category');

		if (!array_key_exists('category_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		// Category
		$this->load->model('catalog/category');

		$category_info = $this->model_catalog_category->getCategory((int)$args['category_id']);

		if (!$category_info || !$category_info['status'] || !in_array($store_info['store_id'], $this->model_catalog_category->getStores($category_info['category_id']))) {
			return ['error' => $this->language->get('error_category')];
		}

		// Description
		$description_data = [];

		$descriptions = $this->model_catalog_category->getDescriptions($category_info['category_id']);

		foreach ($descriptions as $code => $description) {
			$description_data[$code] = [
				'name'             => $description['name'],
				'description'      => $description['description'],
				'meta_title'       => $description['meta_title'],
				'meta_description' => $description['meta_description'],
				'meta_keyword'     => $description['meta_keyword']
			];
		}

		$path = $this->model_catalog_category->getPath($category_info['category_id']);

		// Children
		$children_data = [];

		$filter_data = [
			'filter_store_id'  => $store_info['store_id'],
			'filter_parent_id' => $category_info['category_id'],
			'filter_status'    => true,
			'sort'             => 'sort_order',
			'order'            => 'ASC',
		];

		$children = $this->model_catalog_category->getCategories($filter_data);

		foreach ($children as $child) {
			$child_description_data = [];

			$child_descriptions = $this->model_catalog_category->getDescriptions($child['category_id']);

			foreach ($child_descriptions as $code => $child_description) {
				$child_description_data[$code] = ['name' => $child_description['name']];
			}

			$children_data[] = [
				'category_id' => $child['category_id'],
				'description' => $child_description_data,
				'image'       => $child['image'],
				'path'        => $path . '_' . $child['category_id'],
				'sort_order'  => $child['sort_order']
			];
		}

		$category_data = [
			'category_id' => $category_info['category_id'],
			'description' => $description_data,
			'image'       => $category_info['image'],
			'path'        => $path,
			'children'    => $children_data,
			'sort_order'  => $category_info['sort_order']
		];

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/catalog/';
		$filename = 'category-' . $category_info['category_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($category_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $category_info['name'])];
	}

	/**
	 * Product
	 *
	 * Generate product category task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function product(array $args = []): array {
		$this->load->language('task/catalog/category');

		if (!array_key_exists('category_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		// Category
		$this->load->model('catalog/category');

		$category_info = $this->model_catalog_category->getCategory((int)$args['category_id']);

		if (!$category_info || !$category_info['status'] || !in_array($store_info['store_id'], $this->model_catalog_category->getStores($category_info['category_id']))) {
			return ['success' => $this->language->get('error_category')];
		}

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/catalog/';
		$filename = 'category-product-' . $category_info['category_id'] . '.csv';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		$filter_data = [
			'filter_store_id'    => $store_info['store_id'],
			'filter_category_id' => $category_info['category_id'],
			'filter_status'      => true,
			'sort'               => 'name',
			'order'              => 'ASC',
		];

		$this->load->model('catalog/product');

		if (!file_put_contents($directory . $filename, implode(',', array_column($this->model_catalog_product->getProducts($filter_data), 'product_id')))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_product'), $store_info['name'], $category_info['name'])];
	}

	/*
	 * Delete files based on country ID
	 *
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/catalog/category');

		if (!array_key_exists('category_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		$file = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/catalog/category-' . (int)$args['category_id'] . '.json';

		if (is_file($file)) {
			unlink($file);
		}

		$file = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/catalog/category-product-' . (int)$args['category_id'] . '.csv';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => sprintf($this->language->get('text_delete'), $store_info['name'])];
	}
}
