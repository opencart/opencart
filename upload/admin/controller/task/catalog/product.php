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
	 * Generates all product data based on store
	 *
	 * Adds task to generate product store data.
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(array &$args): array {
		$this->load->language('task/catalog/product');

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

		$filter_data = [
			'filter_store_id' => $args['store_id'],
			'filter_status'   => true
		];

		$this->load->model('catalog/product');

		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

		for ($i = 0; $i <= ceil($product_total / $limit); $i++) {
			$start = $i * $limit;

			$task_data = [
				'code'   => 'product.list.' . $args['store_id'] . '.' . $start . '.' . $limit,
				'action' => 'task/catalog/product.list',
				'args'   => [
					'store_id' => $args['store_id'],
					'start'    => $start,
					'limit'    => $limit
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_task'), $store_info['name'])];
	}

	/*
	 * List
	 *
	 * Generate All Product files.
	 *
	 * @param array<int, mixed> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/product');

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

		$filter_data = [
			'filter_store_id' => $store_info['store_id'],
			'filter_status'   => true,
			'sort'            => 'name',
			'order'           => 'DESC',
			'start'           => $args['start'],
			'limit'           => $args['limit']
		];

		$this->load->model('catalog/product');

		$results = $this->model_catalog_product->getProducts($filter_data);

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'product.info.' . $store_info['store_id'] . '.' . $result['product_id'],
				'action' => 'task/catalog/product.info',
				'args'   => [
					'product_id' => $result['product_id'],
					'store_id'   => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'], $args['start'], $args['limit'])];
	}

	/**
	 * Index
	 *
	 * Generate product task by product ID for each store and language.
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

		if (!$product_info || !$product_info['status'] || !in_array($store_info['store_id'], $this->model_catalog_product->getStores($product_info['product_id']))) {
			return ['success' => $this->language->get('error_product')];
		}

		// Image
		$this->load->model('tool/image');

		if ($product_info['image'] && is_file(DIR_IMAGE . html_entity_decode($product_info['image'], ENT_QUOTES, 'UTF-8'))) {
			$popup = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
			$thumb = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
		} else {
			$popup = '';
			$thumb = '';
		}

		// Images
		$image_data = [];

		$images = $this->model_catalog_product->getImages($product_info['product_id']);

		foreach ($images as $image) {
			if ($image['image'] && is_file(DIR_IMAGE . html_entity_decode($image['image'], ENT_QUOTES, 'UTF-8'))) {
				$image_data[] = [
					'popup' => $this->model_tool_image->resize($image['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')),
					'thumb' => $this->model_tool_image->resize($image['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'))
				];
			}
		}

		// Description
		$description_data = [];

		$descriptions = $this->model_catalog_product->getDescriptions($product_info['product_id']);

		foreach ($descriptions as $code => $description) {
			$description_data[$code] = [
				'name'             => $description['name'],
				'description'      => $description['description'],
				'tag'              => $description['tag'],
				'meta_title'       => $description['meta_title'],
				'meta_description' => $description['meta_description'],
				'meta_keyword'     => $description['meta_keyword']
			];
		}

		// Product Codes
		$product_code_data = [];

		$product_codes = $this->model_catalog_product->getCodes($product_info['product_id']);

		foreach ($product_codes as $product_code) {
			if ($product_code['status']) {
				$product_code_data[] = [
					'code'  => $product_code['code'],
					'value' => $product_code['value']
				];
			}
		}

		// Manufacturer
		$this->load->model('catalog/manufacturer');

		$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

		if ($manufacturer_info) {
			$manufacturer = $manufacturer_info['name'];
		} else {
			$manufacturer = '';
		}

		// Attributes
		$attribute_groups = [];

		$this->load->model('catalog/attribute');

		$attributes = $this->model_catalog_product->getAttributes($product_info['product_id']);

		foreach ($attributes as $attribute) {
			$attribute_info = $this->model_catalog_attribute->getAttribute($attribute['attribute_id']);

			if ($attribute_info) {
				$attribute_description_data = [];

				$product_attribute_descriptions = $this->model_catalog_product->getAttributeDescriptions($product_info['product_id'], $attribute_info['attribute_id']);

				foreach ($product_attribute_descriptions as $code => $product_attribute_description) {
					$attribute_description_info = $this->model_catalog_attribute->getAttributeDescription($attribute_info['attribute_id'], $product_attribute_description['language_id']);

					if ($attribute_description_info) {
						$attribute_description_data[$code] = [
							'name' => $attribute_description_info['name'],
							'text' => $product_attribute_description['text']
						];
					}
				}

				$attribute_groups[$attribute_info['attribute_group_id']][] = [
					'attribute_id' => $attribute_info['attribute_id'],
					'description'  => $attribute_description_data,
					'sort_order'   => $attribute_info['sort_order']
				];
			}
		}

		$attribute_group_data = [];

		foreach ($attribute_groups as $attribute_group_id => $value) {
			$attribute_group_info = $this->model_catalog_attribute->getAttributeGroup($attribute_group_id);

			if ($attribute_group_info) {
				$attribute_group_description_data = [];

				$attribute_group_descriptions = $this->model_catalog_attribute->getDescriptions($attribute_group_info['attribute_group_id']);

				foreach ($attribute_group_descriptions as $code => $attribute_group_description) {
					$attribute_group_description_data[$code] = ['name' => $attribute_group_description['name']];
				}

				$attribute_group_data[] = [
					'attribute_group_id' => $attribute_group_info['attribute_group_id'],
					'description'        => $attribute_group_description_data,
					'attribute'          => $value,
					'sort_order'         => $attribute_group_info['sort_order']
				];
			}
		}

		// Options
		$product_option_data = [];

		$this->load->model('catalog/option');

		$options = $this->model_catalog_product->getOptions($product_info['product_id']);

		foreach ($options as $option) {
			$option_value_data = [];

			if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox') {
				foreach ($option['product_option_value'] as $option_value) {
					$option_value_description_data = [];

					$option_value_descriptions = $this->model_catalog_option->getValueDescriptions($option_value['option_value_id']);

					foreach ($option_value_descriptions as $code => $option_value_description) {
						$option_value_description_data[$code] = ['name' => $option_value_description['name']];
					}

					$option_value_data[] = [
						'product_option_value_id' => $option_value['product_option_value_id'],
						'description'             => $option_value_description_data,
						'image'                   => $option_value['image'],
						'quantity'                => $option_value['quantity'],
						'price'                   => $option_value['price'],
						'points'                  => $option_value['points'],
						'weight'                  => $option_value['weight'],
						'sort_order'              => $option_value['sort_order']
					];
				}
			}

			$option_description_data = [];

			$option_descriptions = $this->model_catalog_option->getDescriptions($option['option_id']);

			foreach ($option_descriptions as $code => $option_description) {
				$option_description_data[$code] = ['name' => $option_description['name']];
			}

			$product_option_data[] = [
				'product_option_id' => $option['product_option_id'],
				'description'       => $option_description_data,
				'type'              => $option['type'],
				'value'             => $option['value'],
				'option_value'      => $option_value_data,
				'validation'        => $option['validation'],
				'required'          => $option['required'],
				'sort_order'        => $option['sort_order']
			];
		}

		// Subscription Plans
		$subscription_plan_data = [];

		$this->load->model('catalog/subscription_plan');

		$product_subscriptions = $this->model_catalog_product->getSubscriptions($product_info['product_id']);

		foreach ($product_subscriptions as $product_subscription) {
			$subscription_info = $this->model_catalog_subscription_plan->getSubscription($product_subscription['product_id']);

			if ($subscription_info) {
				$subscription_plan_data[$product_subscription['customer_group_id']] = [
					'subscription_plan_id' => $subscription_info['subscription_plan_id'],
					'trial_price'          => $product_subscription['trial_price'],
					'trial_frequency '     => $subscription_info['trial_frequency'],
					'trial_duration'       => $subscription_info['trial_duration'],
					'trial_cycle'          => $subscription_info['trial_cycle'],
					'trial_status'         => $subscription_info['trial_status'],
					'price'                => $product_subscription['price'],
					'frequency '           => $subscription_info['frequency'],
					'duration'             => $subscription_info['duration'],
					'cycle'                => $subscription_info['cycle'],
					'status'               => $subscription_info['status'],
					'sort_order'           => $subscription_info['sort_order']
				];
			}
		}

		// Discount
		$discount_data = [];

		$discounts = $this->model_catalog_product->getDiscounts($product_info['product_id']);

		foreach ($discounts as $discount) {
			$discount_data[$discount['customer_group_id']] = [
				'quantity'   => $discount['quantity'],
				'priority'   => $discount['priority'],
				'price'      => $discount['price'],
				'type'       => $discount['type'],
				'special'    => $discount['special'],
				'date_start' => $discount['date_start'],
				'date_end'   => $discount['date_end']
			];
		}

		// Reward Points
		$reward_data = [];

		$rewards = $this->model_catalog_product->getRewards($product_info['product_id']);

		foreach ($rewards as $reward) {
			$reward_data[$reward['customer_group_id']] = ['points' => $reward['points']];
		}

		$product_data = [
			'product_id'         => $product_info['product_id'],
			'popup'              => $popup,
			'thumb'              => $thumb,
			'images'             => $image_data,
			'description'        => $description_data,
			'model'              => $product_info['model'],
			'product_codes'      => $product_code_data,
			'attribute_groups'   => $attribute_group_data,
			'options'            => $product_option_data,
			'subscription_plans' => $subscription_plan_data,
			'location'           => $product_info['location'],
			'variant'            => $product_info['variant'],
			'override'           => $product_info['override'],
			'quantity'           => $product_info['quantity'],
			'minimum'            => $product_info['minimum'],
			'stock_status_id'    => $product_info['stock_status_id'],
			'manufacturer_id'    => $product_info['manufacturer_id'],
			'manufacturer'       => $manufacturer,
			'price'              => $product_info['price'],
			'tax_class_id'       => $product_info['tax_class_id'],
			'discounts'          => $discount_data,
			'points'             => $product_info['points'],
			'rewards'            => $reward_data,
			'date_available'     => $product_info['date_available'],
			'shipping'           => $product_info['shipping'],
			'weight'             => $product_info['weight'],
			'weight_class_id'    => $product_info['weight_class_id'],
			'length'             => $product_info['length'],
			'width'              => $product_info['width'],
			'height'             => $product_info['height'],
			'length_class_id'    => $product_info['length_class_id'],
			'sales'              => $product_info['sales'],
			'rating'             => $product_info['rating'],
			'date_added'         => $product_info['date_added'],
			'date_modified'      => $product_info['date_modified']
		];

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/catalog/product/';
		$filename = 'product-' . $product_info['product_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($product_data))) {
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

		$file = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/catalog/product/product-' . (int)$args['product_id'] . '.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => sprintf($this->language->get('text_delete'), $store_info['name'])];
	}
}
