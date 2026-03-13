<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Product
 *
 * Generates product information for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Product extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate product task by product ID for each store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/product');

		if (!array_key_exists('product_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct((int)$args['product_id']);

		if (!$product_info || !$product_info['status']) {
			return ['success' => $this->language->get('error_product')];
		}

		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = $this->model_catalog_product->getStores($product_info['product_id']);

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'product.info.' . $store_id . '.' . $product_info['product_id'],
				'action' => 'task/catalog/product.info',
				'args'   => [
					'product_id' => $product_info['product_id'],
					'store_id'   => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * Info
	 *
	 * Generate country data by product ID.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/product');

		if (!array_key_exists('product_id', $args)) {
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

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct((int)$args['product_id']);

		if (!$product_info) {
			return ['error' => $this->language->get('error_product')];
		}

		// Image
		$this->load->model('tool/image');

		if ($product_info['image'] && is_file(DIR_IMAGE . html_entity_decode($product_info['image'], ENT_QUOTES, 'UTF-8'))) {
			$data['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			$data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
		} else {
			$data['popup'] = '';
			$data['thumb'] = '';
		}

		$results = $this->model_catalog_product->getCodes($product_info['product_id']);

		foreach ($results as $result) {
			if ($result['status']) {
				$data['product_codes'][] = $result;
			}
		}

		// Manufacturer
		$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

		if ($manufacturer_info) {
			$data['manufacturer'] = $manufacturer_info['name'];
		} else {
			$data['manufacturer'] = '';
		}

		// Images
		$images = $this->model_catalog_product->getImages($product_info['product_id']);

		// Attributes
		$attribute_groups = $this->model_catalog_product->getAttributes($product_info['product_id']);

		$discounts = $this->model_catalog_product->getDiscounts($product_info['product_id']);

		$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
		$filename = 'product-' . $product_info['product_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode(array_merge($product_info, ['description' => $this->model_catalog_product->getDescriptions($product_info['product_id'])], ['images' => $images])))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $product_info['name'])];
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
			$file = DIR_CATALOG . 'view/data/' . parse_url($store_url, PHP_URL_HOST) . '/catalog/product-' . $product_info['product_id'] . '.json';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => sprintf($this->language->get('text_delete'), $product_info['name'])];
	}
}
