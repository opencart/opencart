<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Category
 *
 * Generates category information for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Category extends \Opencart\System\Engine\Controller {
	/**
	 * List
	 *
	 * Generate all category list data for all stores.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/category');

		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'category.list.' . $store_id,
				'action' => 'task/catalog/category.list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate category lists.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/category');

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

		$category_data = [];

		$filter_data = [
			'filter_store_id' => $store_info['store_id'],
			'filter_status'   => true,
			'sort'            => 'sort_order',
			'order'           => 'ASC',
		];

		$this->load->model('catalog/category');

		$results = $this->model_catalog_category->getCategories($filter_data);

		foreach ($results as $result) {
			$description_data = [];

			$descriptions = $this->model_catalog_category->getDescriptions($result['category_id']);

			foreach ($descriptions as $code => $description) {
				$description_data[$code] = ['name' => $description['name']];
			}

			$category_data[] = [
				'category_id' => $result['category_id'],
				'description' => $description_data,
				'image'       => $result['image'],
				'parent_id'   => $result['parent_id']
			];
		}

		$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
		$filename = 'category.yaml';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, oc_yaml_encode($category_data))) {
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

		$this->load->model('catalog/category');

		$category_info = $this->model_catalog_category->getCategory((int)$args['category_id']);

		if (!$category_info || !$category_info['status']) {
			return ['error' => $this->language->get('error_category')];
		}

		$this->load->model('setting/task');

		$store_ids = $this->model_catalog_category->getStores($category_info['category_id']);

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'category._info' . $store_id . '.' . $category_info['category_id'],
				'action' => 'task/catalog/category._info',
				'args'   => [
					'category_id' => $category_info['category_id'],
					'store_id'    => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_info'), $category_info['name'])];
	}

	/*
	 *
	 */
	public function _info(array $args = []): array {
		$this->load->language('task/catalog/category');

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
			return ['error' => $this->language->get('error_category')];
		}

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

		$children_data = [];

		$children = $this->model_catalog_category->getCategories(['filter_parent_id' => $category_info['category_id']]);

		foreach ($children as $child) {
			$store_ids = $this->model_catalog_category->getStores($child['category_id']);

			if (in_array($store_info['store_id'], $store_ids)) {
				$child_description_data = [];

				$child_descriptions = $this->model_catalog_category->getDescriptions($child['category_id']);

				foreach ($child_descriptions as $code => $child_description) {
					$child_description_data[$code] = ['name' => $child_description['name']];
				}

				$children_data[] = [
					'category_id' => $child['category_id'],
					'description' => $child_description_data,
					'image'       => $category_info['image'],
					'sort_order'  => $category_info['sort_order']
				];
			}
		}

		$category_data = [
			'category_id' => $category_info['category_id'],
			'description' => $description_data,
			'image'       => $category_info['image'],
			'parent_id'   => $category_info['parent_id'],
			'path'        => getPath,
			'children'        => $children_data,
			'sort_order'  => $category_info['sort_order']
		];

		$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
		$filename = 'category-' . $category_info['category_id'] . '.yaml';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, oc_yaml_encode($category_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $category_info['name'])];
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

		$this->load->model('catalog/category');

		$category_info = $this->model_catalog_category->getCategory((int)$args['category_id']);

		if (!$category_info) {
			return ['error' => $this->language->get('error_category')];
		}

		$this->load->model('setting/store');

		$store_urls = [HTTP_CATALOG, ...array_column($this->model_setting_store->getStores(), 'url')];

		foreach ($store_urls as $store_url) {
			$file = DIR_CATALOG . 'view/data/' . parse_url($store_url, PHP_URL_HOST) . '/catalog/category-' . $category_info['category_id'] . '.yaml';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => sprintf($this->language->get('text_delete'), $category_info['name'])];
	}
}
