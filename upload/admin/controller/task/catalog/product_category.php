<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Product Category
 *
 * Generates category information for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class ProductCategory extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate product category task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/product_category');

		$this->load->model('catalog/category');

		$category_info = $this->model_catalog_category->getCategory((int)$args['category_id']);

		if (!$category_info || !$category_info['status']) {
			return ['success' => $this->language->get('error_category')];
		}

		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'category.info.' . $store_id . '.' . $category_info['category_id'],
				'action' => 'task/catalog/category.info',
				'args'   => [
					'filter_id' => $category_info['category_id'],
					'store_id'  => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate JSON country list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/product_category');

		if (!array_key_exists('category_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

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

		$this->load->model('catalog/category');

		$category_info = $this->model_catalog_category->getCategory((int)$args['category_id']);

		if (!$category_info || !$category_info['status']) {
			return ['success' => $this->language->get('error_category')];
		}

		$product_data = [];

		$this->load->model('catalog/product');

		$product_ids = $this->model_catalog_product->getProductsByCategoryId($category_info['category_id']);

		foreach ($product_ids as $product_id) {
			$store_ids = $this->model_catalog_product->getStores($product_id);

			if (in_array($store_info['store_id'], $store_ids)) {
				$product_data[] = $product_id;
			}
		}

		$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
		$filename = 'category-' . $category_info['category_id'] . '.csv';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, implode(',', $product_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'], $category_info['name'])];
	}

	/**
	 * Delete
	 *
	 * Delete generated JSON information files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/catalog/product_category');

		if (!array_key_exists('category_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('catalog/category');

		$category_info = $this->model_catalog_category->getCategory((int)$args['category_id']);

		if (!$category_info || !$category_info['status']) {
			return ['success' => $this->language->get('error_category')];
		}

		$this->load->model('setting/store');

		$store_urls = [HTTP_CATALOG, ...array_column($this->model_setting_store->getStores(), 'url')];

		foreach ($store_urls as $store_url) {
			$file = DIR_CATALOG . 'view/data/' . parse_url($store_url, PHP_URL_HOST) . '/catalog/product-category-' . $category_info['category_id'] . '.csv';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => sprintf($this->language->get('text_delete'), $category_info['name'])];
	}
}