<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Filter
 *
 * Generates filter information.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Filter extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate filter list task by store.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array &$args): array {
		$this->load->language('task/catalog/filter');

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

		$this->load->model('setting/task');

		$limit = 1000;

		$this->load->model('catalog/filter');

		$filter_group_total = $this->model_catalog_filter->getTotalFilterGroups();

		for ($i = 0; $i <= ceil($filter_group_total / $limit); $i++) {
			$task_data = [
				'code'   => 'filter.list.' . $store_info['store_id'],
				'action' => 'task/catalog/filter.list',
				'args'   => [
					'store_id' => $store_info['store_id'],
					'start'    => $i * $limit,
					'limit'    => $limit
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_task'), $store_info['name'])];
	}

	public function list(array $args = []): array {
		$this->load->model('setting/task');

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

		// Filter Group
		$this->load->model('catalog/filter');

		$filter_groups = $this->model_catalog_filter->getFilterGroups();

		foreach ($filter_groups as $filter_group) {
			$task_data = [
				'code'   => 'filter.info.' . $store_info['store_id'] . '.' . $filter_group['filter_group_id'],
				'action' => 'task/catalog/filter.info',
				'args'   => [
					'filter_group_id' => $filter_group['filter_group_id'],
					'store_id'        => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);

			// Filters
			$filters = $this->model_catalog_filter->getFilters(['filter_group_id' => $filter_group['filter_group_id']]);

			foreach ($filters as $filter) {
				$task_data = [
					'code'   => 'filter.product.' . $store_info['store_id'] . '.' . $filter['filter_id'],
					'action' => 'task/catalog/filter.product',
					'args'   => [
						'filter_id' => $filter['filter_id'],
						'store_id'  => $store_info['store_id']
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'], $args['start'], $args['limit'])];
	}


	/**
	 * Index
	 *
	 * Generate country task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/filter');

		if (!array_key_exists('filter_group_id', $args)) {
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

		// Filter Group
		$this->load->model('catalog/filter_group');

		$filter_group_info = $this->model_catalog_filter->getFilterGroup((int)$args['filter_group_id']);

		if (!$filter_group_info || !$filter_group_info['status']) {
			return ['success' => $this->language->get('error_filter_group')];
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

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/catalog/';
		$filename = 'filter_group-' . $filter_group_info['filter_group_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($filter_group_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $filter_group_info['name'])];
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
	public function product(array $args = []): array {
		$this->load->language('task/catalog/filter');

		if (!array_key_exists('filter_id', $args)) {
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

		// Filter
		$this->load->model('catalog/filter');

		$filter_info = $this->model_catalog_filter->getFilter((int)$args['filter_id']);

		if (!$filter_info || !$filter_info['status']) {
			return ['success' => $this->language->get('error_filter')];
		}

		$product_data = [];

		$filter_data = [
			'filter_filter_id' => $filter_info['filter_id'],
			'filter_store_id'  => $store_info['store_id'],
			'filter_status'    => true,
			'sort'             => 'name',
			'order'            => 'ASC',
		];

		$this->load->model('catalog/product');

		$products = $this->model_catalog_product->getProducts($filter_data);

		foreach ($products as $product) {
			$product_data[] = $product['product_id'];
		}

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/catalog/';
		$filename = 'filter-product-' . $filter_info['filter_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($product_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_product'), $store_info['name'], $filter_info['name'])];
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
		$this->load->language('task/catalog/filter_group');

		if (!array_key_exists('filter_group_id', $args)) {
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

		$file = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/catalog/filter_group-' . (int)$args['filter_group_id'] . '.json';

		if (is_file($file)) {
			unlink($file);
		}

		$file = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/catalog/filter-product-' . (int)$args['filter_group_id'] . '.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => sprintf($this->language->get('text_delete'), $store_info['name'])];
	}
}

