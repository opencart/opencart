<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Manufacturer
 *
 * Generates manufacturer data for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Manufacturer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate manufacturer task list for each store.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/manufacturer');

		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'manufacturer.list.' . $store_id,
				'action' => 'task/catalog/manufacturer.list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate manufacturer list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/manufacturer');

		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStores((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		$manufacturer_data = [];

		$this->load->model('catalog/manufacturer');

		$manufacturer_ids = $this->model_catalog_manufacturer->getStores($store_info['store_id']);

		foreach ($manufacturer_ids as $manufacturer_id) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

			if ($manufacturer_info && $manufacturer_info['status']) {
				$manufacturer_data[] = $manufacturer_info + ['description' => $this->model_catalog_manufacturer->getDescriptions($manufacturer_info['manufacturer_id'])];
			}
		}

		$sort_order = [];

		foreach ($manufacturer_data as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $manufacturer_data);

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
		$filename = 'manufacturer.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($manufacturer_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'])];
	}

	/**
	 * Info
	 *
	 * Generate manufacturer information.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/manufacturer');

		if (!array_key_exists('manufacturer_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('catalog/manufacturer');

		$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int)$args['manufacturer_id']);

		if (!$manufacturer_info || !$manufacturer_info['status']) {
			return ['error' => $this->language->get('error_manufacturer')];
		}

		$this->load->model('setting/task');

		$store_ids = $this->model_catalog_information->getStores($manufacturer_info['manufacturer_id']);

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'manufacturer._info.' . $store_id . '.' . $manufacturer_info['manufacturer_id'],
				'action' => 'task/catalog/manufacturer._info',
				'args'   => [
					'manufacturer_id' => $manufacturer_info['manufacturer_id'],
					'store_id'        => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_info'), $manufacturer_info['name'])];
	}

	public function _info(array $args = []): array {
		$this->load->language('task/catalog/manufacturer');

		if (!array_key_exists('manufacturer_id', $args)) {
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

		$this->load->model('catalog/manufacturer');

		$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int)$args['manufacturer_id']);

		if (!$manufacturer_info || !$manufacturer_info['status']) {
			return ['error' => $this->language->get('error_manufacturer')];
		}

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/localisation/';
		$filename = 'manufacturer-' . $manufacturer_info['manufacturer_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($manufacturer_info + ['description' => $this->model_catalog_manufacturer->getDescriptions($manufacturer_info['manufacturer_id'])]))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $manufacturer_info['name'])];
	}

	/*
	 * Delete files based on country ID
	 *
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/catalog/language');

		// Refresh Lists
		$task_data = [
			'code'   => 'manufacturer',
			'action' => 'task/catalog/manufacturer',
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

		foreach ($stores as $store) {
			$base = DIR_CATALOG . 'view/data/';
			$directory = parse_url($store['url'], PHP_URL_HOST) . '/localisation/';

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

	/**
	 * Clear
	 *
	 * Delete generated JSON country files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/catalog/language');

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
			foreach ($languages as $language) {
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
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
