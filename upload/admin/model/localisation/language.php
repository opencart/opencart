<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Language
 *
 * Can be loaded using $this->load->model('localisation/language');
 *
 * @package Opencart\Admin\Model\Localisation
 */
class Language extends \Opencart\System\Engine\Model {
	/**
	 * Add Language
	 *
	 * Create a new language record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new language record
	 *
	 * @example
	 *
	 * $language_data = [
	 *     'name'       => 'Language Name',
	 *     'code'       => 'Language Code',
	 *     'locale'     => 'Language Locale',
	 *     'extension'  => '',
	 *     'sort_order' => 0,
	 *     'status'     => 0
	 * ];
	 *
	 * $this->load->model('localisation/language');
	 *
	 * $language_id = $this->model_localisation_language->addLanguage($language_data);
	 */
	public function addLanguage(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "language` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `locale` = '" . $this->db->escape((string)$data['locale']) . "', `extension` = '" . $this->db->escape((string)$data['extension']) . "', `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "'");

		$this->cache->delete('language');

		$language_id = $this->db->getLastId();

		// Attribute
		$this->load->model('catalog/attribute');

		$results = $this->model_catalog_attribute->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $attribute) {
			$this->model_catalog_attribute->addDescription((int)$attribute['attribute_id'], $language_id, $attribute);
		}

		// Attribute Group
		$this->load->model('catalog/attribute_group');

		$results = $this->model_catalog_attribute_group->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $attribute_group) {
			$this->model_catalog_attribute_group->addDescription((int)$attribute_group['attribute_group_id'], $language_id, $attribute_group);
		}

		// Banner
		$this->load->model('design/banner');

		$results = $this->model_design_banner->getImagesByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $banner_image) {
			$this->model_design_banner->addImage($banner_image['banner_id'], $language_id, $banner_image);
		}

		// Category
		$this->load->model('catalog/category');

		$results = $this->model_catalog_category->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $category) {
			$this->model_catalog_category->addDescription((int)$category['category_id'], $language_id, $category);
		}

		// Country
		$this->load->model('localisation/country');

		$results = $this->model_localisation_country->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $country) {
			$this->model_localisation_country->addDescription((int)$country['country_id'], $language_id, $country);
		}

		// Customer Group
		$this->load->model('customer/customer_group');

		$results = $this->model_customer_customer_group->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $customer_group) {
			$this->model_customer_customer_group->addDescription((int)$customer_group['customer_group_id'], $language_id, $customer_group);
		}

		// Custom Field
		$this->load->model('customer/custom_field');

		$results = $this->model_customer_custom_field->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $custom_field) {
			$this->model_customer_custom_field->addDescription((int)$custom_field['custom_field_id'], $language_id, $custom_field);
		}

		// Custom Field Value
		$results = $this->model_customer_custom_field->getValueDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $custom_field_value) {
			$this->model_customer_custom_field->addValueDescription((int)$custom_field_value['custom_field_value_id'], (int)$custom_field_value['custom_field_id'], $language_id, $custom_field_value);
		}

		// Download
		$this->load->model('catalog/download');

		$results = $this->model_catalog_download->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $download) {
			$this->model_catalog_download->addDescription((int)$download['download_id'], $language_id, $download);
		}

		// Filter
		$this->load->model('catalog/filter');

		$results = $this->model_catalog_filter->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $filter) {
			$this->model_catalog_filter->addDescription((int)$filter['filter_id'], $language_id, $filter);
		}

		// Filter Group
		$this->load->model('catalog/filter_group');

		$results = $this->model_catalog_filter_group->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $filter_group) {
			$this->model_catalog_filter_group->addDescription((int)$filter_group['filter_group_id'], $language_id, $filter_group);
		}

		// Information
		$this->load->model('catalog/information');

		$results = $this->model_catalog_information->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $information) {
			$this->model_catalog_information->addDescription((int)$information['information_id'], $language_id, $information);
		}

		// Length
		$this->load->model('localisation/length_class');

		$results = $this->model_localisation_length_class->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $length) {
			$this->model_localisation_length_class->addDescription((int)$length['length_class_id'], $language_id, $length);
		}

		// Manufacturer
		$this->load->model('catalog/manufacturer');

		$results = $this->model_catalog_manufacturer->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $manufacturer) {
			$this->model_catalog_manufacturer->addDescription((int)$manufacturer['manufacturer_id'], $language_id, $manufacturer);
		}

		// Option
		$this->load->model('catalog/option');

		$results = $this->model_catalog_option->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $option) {
			$this->model_catalog_option->addDescription((int)$option['option_id'], $language_id, $option);
		}

		// Option Value
		$results = $this->model_catalog_option->getValueDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $option_value) {
			$this->model_catalog_option->addValueDescription((int)$option_value['option_value_id'], (int)$option_value['option_id'], $language_id, $option_value);
		}

		// Order Status
		$this->load->model('localisation/order_status');

		$results = $this->model_localisation_order_status->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $order_status) {
			$this->model_localisation_order_status->addDescription((int)$order_status['order_status_id'], $language_id, $order_status);
		}

		// Product
		$this->load->model('catalog/product');

		$results = $this->model_catalog_product->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $product) {
			$this->model_catalog_product->addDescription((int)$product['product_id'], $language_id, $product);
		}

		// Product Attribute
		$results = $this->model_catalog_product->getAttributesByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $product_attribute) {
			$this->model_catalog_product->addAttribute((int)$product_attribute['product_id'], (int)$product_attribute['attribute_id'], $language_id, $product_attribute);
		}

		// Return Action
		$this->load->model('localisation/return_action');

		$results = $this->model_localisation_return_action->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $return_action) {
			$this->model_localisation_return_action->addDescription((int)$return_action['return_action_id'], $language_id, $return_action);
		}

		// Return Reason
		$this->load->model('localisation/return_reason');

		$results = $this->model_localisation_return_reason->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $return_reason) {
			$this->model_localisation_return_reason->addDescription((int)$return_reason['return_reason_id'], $language_id, $return_reason);
		}

		// Return Status
		$this->load->model('localisation/return_status');

		$results = $this->model_localisation_return_status->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $return_status) {
			$this->model_localisation_return_status->addDescription((int)$return_status['return_status_id'], $language_id, $return_status);
		}

		// Stock Status
		$this->load->model('localisation/stock_status');

		$results = $this->model_localisation_stock_status->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $stock_status) {
			$this->model_localisation_stock_status->addDescription((int)$stock_status['stock_status_id'], $language_id, $stock_status);
		}

		// Subscription Plan
		$this->load->model('catalog/subscription_plan');

		$results = $this->model_catalog_subscription_plan->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $subscription_plan) {
			$this->model_catalog_subscription_plan->addDescription((int)$subscription_plan['subscription_plan_id'], $language_id, $subscription_plan);
		}

		// Subscription Status
		$this->load->model('localisation/subscription_status');

		$results = $this->model_localisation_subscription_status->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $subscription) {
			$this->model_localisation_subscription_status->addDescription((int)$subscription['subscription_status_id'], $language_id, $subscription);
		}

		// SEO
		$this->load->model('design/seo_url');

		$results = $this->model_design_seo_url->getSeoUrlsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $seo_url) {
			$this->model_design_seo_url->addSeoUrl($seo_url['key'], $seo_url['value'], $seo_url['keyword'], $seo_url['store_id'], $language_id, $seo_url['sort_order']);
		}

		// Setup new SEO URL language keyword
		$languages = $this->getLanguages();

		foreach ($languages as $language) {
			// Set default store
			$this->model_design_seo_url->addSeoUrl('language', (string)$data['code'], (string)$data['code'], 0, $language['language_id'], -2);
		}

		// Set default store
		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$this->model_design_seo_url->addSeoUrl('language', (string)$data['code'], (string)$data['code'], $store['store_id'], $language['language_id'], -2);
			}
		}

		// Topic
		$this->load->model('cms/topic');

		$results = $this->model_cms_topic->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $topic) {
			$this->model_cms_topic->addDescription((int)$topic['topic_id'], $language_id, $topic);
		}

		// Weight Class
		$this->load->model('localisation/weight_class');

		$results = $this->model_localisation_weight_class->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $weight_class) {
			$this->model_localisation_weight_class->addDescription((int)$weight_class['weight_class_id'], $language_id, $weight_class);
		}

		// Zone
		$this->load->model('localisation/zone');

		$results = $this->model_localisation_zone->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $zone) {
			$this->model_localisation_zone->addDescription((int)$zone['zone_id'], $language_id, $zone);
		}

		return $language_id;
	}

	/**
	 * Edit Language
	 *
	 * Edit language record in the database.
	 *
	 * @param int                  $language_id primary key of the language record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $language_data = [
	 *     'name'       => 'Language Name',
	 *     'code'       => 'Language Code',
	 *     'locale'     => 'Language Locale',
	 *     'extension'  => '',
	 *     'sort_order' => 0,
	 *     'status'     => 1
	 * ];
	 *
	 * $this->load->model('localisation/language');
	 *
	 * $this->model_localisation_language->editLanguage($language_id, $language_data);
	 */
	public function editLanguage(int $language_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "language` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `locale` = '" . $this->db->escape((string)$data['locale']) . "', `extension` = '" . $this->db->escape((string)$data['extension']) . "', `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "' WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('language');
	}

	/**
	 * Delete Language
	 *
	 * Delete language record in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/language');
	 *
	 * $this->model_localisation_language->deleteLanguage($language_id);
	 */
	public function deleteLanguage(int $language_id): void {
		$language_info = $this->getLanguage($language_id);

		$this->db->query("DELETE FROM `" . DB_PREFIX . "language` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('language');

		// Article
		$this->load->model('cms/article');

		$this->model_cms_article->deleteDescriptionsByLanguageId($language_id);

		// Attribute
		$this->load->model('catalog/attribute');

		$this->model_catalog_attribute->deleteDescriptionsByLanguageId($language_id);

		// Attribute Group
		$this->load->model('catalog/attribute_group');

		$this->model_catalog_attribute_group->deleteDescriptionsByLanguageId($language_id);

		// Banner
		$this->load->model('design/banner');

		$this->model_design_banner->deleteImagesByLanguageId($language_id);

		// Category
		$this->load->model('catalog/category');

		$this->model_catalog_category->deleteDescriptionsByLanguageId($language_id);

		// Country
		$this->load->model('localisation/country');

		$this->model_localisation_country->deleteDescriptionsByLanguageId($language_id);

		// Customer Group
		$this->load->model('customer/customer_group');

		$this->model_customer_customer_group->deleteDescriptionsByLanguageId($language_id);

		// Custom Field
		$this->load->model('customer/custom_field');

		$this->model_customer_custom_field->deleteDescriptionsByLanguageId($language_id);
		$this->model_customer_custom_field->deleteValueDescriptionsByLanguageId($language_id);

		// Download
		$this->load->model('catalog/download');

		$this->model_catalog_download->deleteDescriptionsByLanguageId($language_id);

		// Filter
		$this->load->model('catalog/filter');

		$this->model_catalog_filter->deleteDescriptionsByLanguageId($language_id);

		// Filter Group
		$this->load->model('catalog/filter_group');

		$this->model_catalog_filter_group->deleteDescriptionsByLanguageId($language_id);

		// Information
		$this->load->model('catalog/information');

		$this->model_catalog_information->deleteDescriptionsByLanguageId($language_id);

		// Length Class
		$this->load->model('localisation/length_class');

		$this->model_localisation_length_class->deleteDescriptionsByLanguageId($language_id);

		// Manufacturer
		$this->load->model('catalog/manufacturer');

		$this->model_catalog_manufacturer->deleteDescriptionsByLanguageId($language_id);

		// Option
		$this->load->model('catalog/option');

		$this->model_catalog_option->deleteDescriptionsByLanguageId($language_id);
		$this->model_catalog_option->deleteValueDescriptionsByLanguageId($language_id);

		// Order Status
		$this->load->model('localisation/order_status');

		$this->model_localisation_order_status->deleteOrderStatusesByLanguageId($language_id);

		// Product
		$this->load->model('catalog/product');

		$this->model_catalog_product->deleteDescriptionsByLanguageId($language_id);
		$this->model_catalog_product->deleteAttributesByLanguageId($language_id);

		// Return Action
		$this->load->model('localisation/return_action');

		$this->model_localisation_return_action->deleteReturnActionsByLanguageId($language_id);

		// Return Reason
		$this->load->model('localisation/return_reason');

		$this->model_localisation_return_reason->deleteReturnReasonsByLanguageId($language_id);

		// Return Status
		$this->load->model('localisation/return_status');

		$this->model_localisation_return_status->deleteReturnStatusesByLanguageId($language_id);

		// Stock Status
		$this->load->model('localisation/stock_status');

		$this->model_localisation_stock_status->deleteStockStatusesByLanguageId($language_id);

		// Weight Class
		$this->load->model('localisation/weight_class');

		$this->model_localisation_weight_class->deleteDescriptionsByLanguageId($language_id);

		// Subscription Status
		$this->load->model('localisation/subscription_status');

		$this->model_localisation_subscription_status->deleteStockStatusesByLanguageId($language_id);

		// SEO
		$this->load->model('design/seo_url');

		$this->model_design_seo_url->deleteSeoUrlsByLanguageId($language_id);

		$this->model_design_seo_url->deleteSeoUrlsByKeyValue('language', $language_info['code']);

		// Topic Status
		$this->load->model('cms/topic');

		$this->model_cms_topic->deleteDescriptionsByLanguageId($language_id);

		// Zone
		$this->load->model('localisation/zone');

		$this->model_localisation_zone->deleteDescriptionsByLanguageId($language_id);
	}

	/**
	 * Get Language
	 *
	 * Get the record of the language record in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<string, mixed> language record that has language ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/language');
	 *
	 * $language_info = $this->model_localisation_language->getLanguage($language_id);
	 */
	public function getLanguage(int $language_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "language` WHERE `language_id` = '" . (int)$language_id . "'");

		$language = $query->row;

		if ($language) {
			$language['image'] = HTTP_CATALOG;

			if (!$language['extension']) {
				$language['image'] .= 'catalog/';
			} else {
				$language['image'] .= 'extension/' . $language['extension'] . '/catalog/';
			}

			$language['image'] .= 'language/' . $language['code'] . '/' . $language['code'] . '.png';
		}

		return $language;
	}

	/**
	 * Get Language By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('localisation/language');
	 *
	 * $language_info = $this->model_localisation_language->getLanguageByCode($code);
	 */
	public function getLanguageByCode(string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE `code` = '" . $this->db->escape($code) . "'");

		$language = $query->row;

		if ($language) {
			$language['image'] = HTTP_CATALOG;

			if (!$language['extension']) {
				$language['image'] .= 'catalog/';
			} else {
				$language['image'] .= 'extension/' . $language['extension'] . '/catalog/';
			}

			$language['image'] .= 'language/' . $language['code'] . '/' . $language['code'] . '.png';
		}

		return $language;
	}

	/**
	 * Get Languages
	 *
	 * Get the record of the language records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<string, array<string, mixed>> language records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'name',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('localisation/language');
	 *
	 * $languages = $this->model_localisation_language->getLanguages($filter_data);
	 */
	public function getLanguages(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "language`";

		$sort_data = [
			'name',
			'code',
			'sort_order'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `sort_order`, `name`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$results = $this->cache->get('language.' . md5($sql));

		if (!$results) {
			$query = $this->db->query($sql);

			$results = $query->rows;

			$this->cache->set('language.' . md5($sql), $results);
		}

		$language_data = [];

		foreach ($results as $result) {
			$image = HTTP_CATALOG;

			if (!$result['extension']) {
				$image .= 'catalog/';
			} else {
				$image .= 'extension/' . $result['extension'] . '/catalog/';
			}

			$language_data[$result['language_id']] = $result + ['image' => $image . 'language/' . $result['code'] . '/' . $result['code'] . '.png'];
		}

		return $language_data;
	}

	/**
	 * Get Languages By Extension
	 *
	 * @param string $extension
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $this->load->model('localisation/language');
	 *
	 * $results = $this->model_localisation_language->getLanguagesByExtension($extension);
	 */
	public function getLanguagesByExtension(string $extension): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE `extension` = '" . $this->db->escape($extension) . "'");

		return $query->rows;
	}

	/**
	 * Get Total Languages
	 *
	 * Get the total number of language records in the database.
	 *
	 * @return int total number of language records
	 *
	 * @example
	 *
	 * $this->load->model('localisation/language');
	 *
	 * $language_total = $this->model_localisation_language->getTotalLanguages();
	 */
	public function getTotalLanguages(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "language`");

		return (int)$query->row['total'];
	}
}
