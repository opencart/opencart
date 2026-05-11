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
			$product_info['popup'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			$product_info['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
		} else {
			$product_info['popup'] = '';
			$product_info['thumb'] = '';
		}

		// Images
		$product_info['images'] = [];

		$results = $this->model_catalog_product->getImages($product_info['product_id']);

		foreach ($results as $result) {
			if ($result['image'] && is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
				$product_info['images'][] = [
					'popup' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'))
				];
			}
		}

		// Product Codes
		$product_info['product_code'] = [];

		$results = $this->model_catalog_product->getCodes($product_info['product_id']);

		foreach ($results as $result) {
			if ($result['status']) {
				$product_info['product_code'][] = $result;
			}
		}

		// Manufacturer
		$this->load->model('catalog/manufacturer');

		$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

		if ($manufacturer_info) {
			$product_info['manufacturer'] = $manufacturer_info['name'];
		} else {
			$product_info['manufacturer'] = '';
		}

		$product_info['discount'] = $this->model_catalog_product->getDiscounts($product_info['product_id']);

		// Attributes
		$attribute_groups = [];

		$this->load->model('catalog/attribute');

		$results = $this->model_catalog_product->getAttributes($product_info['product_id']);

		foreach ($results as $result) {
			$attribute_info = $this->model_catalog_attribute->getAttribute($result['attribute_id']);

			if ($attribute_info) {
				$description_data = [];

				$descriptions = $this->model_catalog_product->getAttributeDescriptions($result['product_id'], $result['attribute_id']);

				foreach ($descriptions as $code => $description) {
					$description_data[$code] = array_merge($description, $this->model_catalog_attribute->getAttributeDescription($result['attribute_id'], $result['language_id']));
				}

				$attribute_groups[$attribute_info['attribute_group_id']][] = array_merge($attribute_info, ['description' => $description_data]);
			}
		}

		$product_info['attribute_group'] = [];

		foreach ($attribute_groups as $attribute_group_id => $value) {
			$attribute_group_info = $this->model_catalog_attribute->getAttributeGroup($attribute_group_id);

			if ($attribute_group_info) {
				$product_info['attribute_group'][] = array_merge($attribute_group_info, ['description' => $this->model_catalog_attribute->getDescriptions($attribute_group_info['attribute_group_id'])], ['attribute' => $value]);
			}
		}

		// Options
		$product_info['option'] = [];

		$this->load->model('catalog/option');

		$results = $this->model_catalog_product->getOptions($product_info['product_id']);

		foreach ($results as $result) {
			$option_values = [];

			if ($result['type'] == 'select' || $result['type'] == 'radio' || $result['type'] == 'checkbox') {
				foreach ($result['product_option_value'] as $product_option_value) {
					$option_values[] = array_merge($product_option_value, ['description' => $this->model_catalog_option->getValueDescriptions($product_option_value['option_value_id'])]);
				}
			}

			unset($result['product_option_value']);

			$product_info['option'][] = array_merge($result, ['option_value' => $option_values], ['description' => $this->model_catalog_option->getDescriptions($result['option_id'])]);
		}

		// Subscription Plans
		$product_info['subscription_plans'] = [];

		$this->load->model('catalog/subscription_plan');

		$results = $this->model_catalog_product->getSubscriptions($product_info['product_id']);

		foreach ($results as $result) {
			$product_info['subscription_plans'][] = array_merge($result, ['description' => $this->model_catalog_subscription_plan->getDescriptions($result['subscription_plan_id'])]);
		}







		$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
		$filename = 'product-' . $product_info['product_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode(array_merge($product_info, ['description' => $this->model_catalog_product->getDescriptions($product_info['product_id'])])))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $product_info['name'])];
	}

	public function category() {




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
