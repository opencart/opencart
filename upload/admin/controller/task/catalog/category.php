<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Category
 *
 * Generates category data files
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
				'code'   => 'category',
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

		$this->load->model('catalog/category');

		$category_ids = $this->model_catalog_category->getStores($store_info['store_id']);

		foreach ($category_ids as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info && $category_info['status']) {
				$category_data[] = $category_info + ['description' => $this->model_catalog_category->getDescriptions($category_info['category_id'])];
			}
		}

		$sort_order = [];

		foreach ($category_data as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $category_data);

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
		$filename = 'category.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($category_data))) {
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
			return ['error' => $this->language->get('error_country')];
		}

		$this->load->model('setting/task');

		$store_ids = $this->model_catalog_information->getStores($category_info['category_id']);

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'category._info' . $store_id . '.' . $category_info['category_id'],
				'action' => 'task/catalog/category._info',
				'args'   => [
					'country_id' => $category_info['category_id'],
					'store_id'   => $store_id
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
			'name' => $this->config->get('config_name'),
			'url'  => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStores((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		$this->load->model('catalog/category');

		$category_info = $this->model_catalog_category->getCategory((int)$args['category_id']);

		if (!$category_info || !$category_info['status']) {
			return ['error' => $this->language->get('error_category')];
		}

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
		$filename = 'category-' . $category_info['category_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($category_info + ['description' => $this->model_catalog_category->getDescriptions($category_info['category_id'])]))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $category_info['name'])];
	}

	/*
	 * Delete files based on country ID
	 *
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/catalog/language');

		// Refresh Lists
		$task_data = [
			'code'   => 'country',
			'action' => 'task/catalog/country.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Delete pages
		$stores = [];

		$stores[] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$stores = array_merge($stores, $this->model_setting_store->getStores());

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			$base = DIR_CATALOG . 'view/data/';
			$directory = parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/localisation/';

			$file = $base . $directory . 'country.json';

			if (is_file($file)) {
				unlink($file);
			}

			$files = oc_directory_read($base . $directory, false, '/country\-.+\.json$/');

			foreach ($files as $file) {
				unlink($file);
			}
		}

		return ['success' => $this->language->get('text_delete')];
	}
}
