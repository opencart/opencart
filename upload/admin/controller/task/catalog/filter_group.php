<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Filter
 *
 * Generates filter information for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class FilterGroup extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate country task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/filter_group');

		if (!array_key_exists('filter_group_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('catalog/filter_group');

		$filter_group_info = $this->model_catalog_filter->getFilterGroup((int)$args['filter_group_id']);

		if (!$filter_group_info || !$filter_group_info['status']) {
			return ['success' => $this->language->get('error_filter_group')];
		}

		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'filter_group.info.' . $store_id . '.' . $filter_group_info['filter_group_id'],
				'action' => 'task/catalog/filter_group.info',
				'args'   => [
					'filter_group_id' => $filter_group_info['filter_group_id'],
					'store_id'        => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * Info
	 *
	 * Generate filter_group information.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/filter_group');

		if (!array_key_exists('filter_group_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		$this->load->model('catalog/filter_group');

		$filter_group_info = $this->model_catalog_filter->getFilterGroup((int)$args['filter_group_id']);

		if (!$filter_group_info) {
			return ['error' => $this->language->get('error_filter_group')];
		}

		// Description
		$description_data = [];

		$descriptions = $this->model_catalog_filter->getDescriptions($filter_group_info['filter_group_id']);

		foreach ($descriptions as $code => $description) {
			$description_data[$code] = ['name' => $description['name']];
		}

		// Filters
		$filter_data = [];

		$filters = $this->model_catalog_filter->getFilters($filter_group_info['filter_group_id']);

		foreach ($filters as $filter) {
			$filter_description_data = [];

			$filter_descriptions = $this->model_catalog_filter->getFilterDescriptions($filter['filter_id']);

			foreach ($filter_descriptions as $code => $filter_description) {
				$filter_description_data[$code] = ['name' => $filter_description['name']];
			}

			$filter_data[] = [
				'filter_id'   => $filter['filter_id'],
				'description' => $filter_description_data
			];
		}

		$filter_group_data = [
			'filter_group_id' => $filter_group_info['filter_group_id'],
			'description'     => $description_data,
			'filters'         => $filter_data
		];

		$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
		$filename = 'filter_group-' . $filter_group_info['filter_group_id'] . '.yaml';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, oc_yaml_encode($filter_group_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $filter_group_info['name'])];
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
		$this->load->language('task/catalog/product');

		if (!array_key_exists('product_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct((int)$args['product_id']);

		if (!$product_info) {
			return ['error' => $this->language->get('error_product')];
		}

		$this->load->model('setting/store');

		$store_urls = [HTTP_CATALOG, ...array_column($this->model_setting_store->getStores(), 'url')];

		foreach ($store_urls as $store_url) {
			$file = DIR_CATALOG . 'view/data/' . parse_url($store_url, PHP_URL_HOST) . '/catalog/product-' . $product_info['product_id'] . '.yaml';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => sprintf($this->language->get('text_delete'), $product_info['name'])];
	}
}

