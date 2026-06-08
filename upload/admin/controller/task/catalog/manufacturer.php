<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Manufacturer
 *
 * Generates manufacturer information for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Manufacturer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates product manufacturer for all stores.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/manufacturer');

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

		// Manufacturer
		$manufacturer_data = [];

		$filter_data = [
			'filter_store_id' => $store_info['store_id'],
			'filter_status'   => true,
			'sort'            => 'sort_order',
			'order'           => 'ASC',
		];

		$this->load->model('catalog/manufacturer');

		$results = $this->model_catalog_manufacturer->getManufacturers($filter_data);

		foreach ($results as $result) {
			$description_data = [];

			$descriptions = $this->model_catalog_manufacturer->getDescriptions($result['manufacturer_id']);

			foreach ($descriptions as $code => $description) {
				$description_data[$code] = ['name' => $description['name']];
			}

			$manufacturer_data[] = [
				'manufacturer_id' => $result['manufacturer_id'],
				'description'     => $description_data
			];
		}

		$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
		$filename = 'manufacturer.yaml';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, oc_yaml_encode($manufacturer_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list')];
	}

	public function info(array $args = []): array {
		$this->load->language('task/catalog/manufacturer');

		if (!array_key_exists('manufacturer_id', $args)) {
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

		// Manufacturer
		$this->load->model('catalog/manufacturer');

		$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int)$args['manufacturer_id']);

		if (!$manufacturer_info || !$manufacturer_info['status'] || !in_array($manufacturer_info['information_id'], $this->model_catalog_manufacturer->getStores($manufacturer_info['manufacturer_id']))) {
			return ['error' => $this->language->get('error_manufacturer')];
		}

		// Description
		$description_data = [];

		$descriptions = $this->model_catalog_manufacturer->getDescriptions($manufacturer_info['manufacturer_id']);

		foreach ($descriptions as $code => $description) {
			$description_data[$code] = [
				'name'             => $description['name'],
				'description'      => $description['description'],
				'meta_title'       => $description['meta_title'],
				'meta_description' => $description['meta_description'],
				'meta_keyword'     => $description['meta_keyword']
			];
		}

		$manufacturer_data = [
			'manufacturer_id' => $manufacturer_info['manufacturer_id'],
			'description'     => $description_data,
			'image'           => $manufacturer_info['image']
		];

		$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
		$filename = 'manufacturer-' . $manufacturer_info['manufacturer_id'] . '.yaml';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, oc_yaml_encode($manufacturer_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $manufacturer_info['name'])];
	}

	/**
	 * product
	 *
	 * Generate JSON country list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function product(array $args = []): array {
		$this->load->language('task/catalog/manufacturer');

		if (!array_key_exists('manufacturer_id', $args)) {
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

		// Manufacturer
		$this->load->model('catalog/manufacturer');

		$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer((int)$args['manufacturer_id']);

		if (!$manufacturer_info || !$manufacturer_info['status']) {
			return ['success' => $this->language->get('error_manufacturer')];
		}

		$filter_data = [
			'filter_store_id'        => $store_info['store_id'],
			'filter_manufacturer_id' => $manufacturer_info['manufacturer_id'],
			'filter_status'          => true,
			'sort'                   => 'name',
			'order'                  => 'ASC',
		];

		$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
		$filename = 'manufacturer-product-' . $manufacturer_info['manufacturer_id'] . '.csv';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		$this->load->model('catalog/product');

		if (!file_put_contents($directory . $filename, implode(',', array_column($this->model_catalog_product->getProducts($filter_data), 'product_id')))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'], $manufacturer_info['name'])];
	}

	/**
	 * Delete
	 *
	 * Delete generated JSON manufacturer files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/catalog/manufacturer');

		if (!array_key_exists('manufacturer_id', $args)) {
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

		$file = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/manufacturer-' . (int)$args['manufacturer_id'] . '.yaml';

		if (is_file($file)) {
			unlink($file);
		}

		$file = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/manufacturer-product-' . (int)$args['manufacturer_id'] . '.csv';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_delete')];
	}
}
