<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Tag
 *
 * Generates tag information for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Tag extends \Opencart\System\Engine\Controller {
	/**
	 * List
	 *
	 * Generate csv file with products ID's related to tags.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/tag');

		if (!array_key_exists('tag', $args)) {
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

		$product_data = [];

		$this->load->model('catalog/product');

		$product_ids = $this->model_catalog_product->getProductsByFilterId($filter_info['filter_id']);

		foreach ($product_ids as $product_id) {
			$store_ids = $this->model_catalog_product->getStores($product_id);

			if (in_array($store_info['store_id'], $store_ids)) {
				$product_data[] = $product_id;
			}
		}

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/catalog/';
		$filename = 'filter-product-' . $filter_info['filter_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, implode(',', $product_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'], $filter_info['name'])];
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

