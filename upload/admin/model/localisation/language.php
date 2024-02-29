<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Language
 *
 * @package Opencart\Admin\Model\Localisation
 */
class Language extends \Opencart\System\Engine\Model {
	/**
	 * Add Language
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function addLanguage(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "language` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `locale` = '" . $this->db->escape((string)$data['locale']) . "', `extension` = '" . $this->db->escape((string)$data['extension']) . "', `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "'");

		$this->cache->delete('language');

		$language_id = $this->db->getLastId();

		// Attribute
		$this->load->model('catalog/attribute');

		$results = $this->model_catalog_attribute->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $attribute) {
			$this->model_catalog_attribute->addDescription($attribute['attribute_id'], $language_id, $attribute);
		}

		// Attribute Group
		$this->load->model('catalog/attribute_group');

		$results = $this->model_catalog_attribute_group->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $attribute_group) {
			$this->model_catalog_attribute->addDescription($attribute['attribute_id'], $language_id, $attribute_group);
		}

		// Banner
		$this->load->model('design/banner');

		$results = $this->model_design_banner->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $banner_image) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "banner_image` SET `banner_id` = '" . (int)$banner_image['banner_id'] . "', `language_id` = '" . (int)$language_id . "', `title` = '" . $this->db->escape($banner_image['title']) . "', `link` = '" . $this->db->escape($banner_image['link']) . "', `image` = '" . $this->db->escape($banner_image['image']) . "', `sort_order` = '" . (int)$banner_image['sort_order'] . "'");
		}

		// Category
		$this->load->model('catalog/category');

		$results = $this->model_catalog_category->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $category) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "category_description` SET `category_id` = '" . (int)$category['category_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($category['name']) . "', `description` = '" . $this->db->escape($category['description']) . "', `meta_title` = '" . $this->db->escape($category['meta_title']) . "', `meta_description` = '" . $this->db->escape($category['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($category['meta_keyword']) . "'");
		}

		// Customer Group
		$this->load->model('customer/customer_group');

		$results = $this->model_customer_customer_group->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_group_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($results as $customer_group) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_group_description` SET `customer_group_id` = '" . (int)$customer_group['customer_group_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($customer_group['name']) . "', `description` = '" . $this->db->escape($customer_group['description']) . "'");
		}

		// Custom Field
		$this->load->model('customer/custom_field');

		$results = $this->model_customer_custom_field->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $custom_field) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "custom_field_description` SET `custom_field_id` = '" . (int)$custom_field['custom_field_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($custom_field['name']) . "'");
		}

		// Custom Field Value
		$results = $this->model_customer_custom_field->getValueDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $custom_field_value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "custom_field_value_description` SET `custom_field_value_id` = '" . (int)$custom_field_value['custom_field_value_id'] . "', `language_id` = '" . (int)$language_id . "', `custom_field_id` = '" . (int)$custom_field_value['custom_field_id'] . "', `name` = '" . $this->db->escape($custom_field_value['name']) . "'");
		}

		// Download
		$this->load->model('catalog/download');

		$results = $this->model_catalog_download->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $download) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "download_description` SET `download_id` = '" . (int)$download['download_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($download['name']) . "'");
		}

		// Filter
		$this->load->model('catalog/filter');

		$results = $this->model_catalog_filter->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $filter) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_description` SET `filter_id` = '" . (int)$filter['filter_id'] . "', `language_id` = '" . (int)$language_id . "', `filter_group_id` = '" . (int)$filter['filter_group_id'] . "', `name` = '" . $this->db->escape($filter['name']) . "'");
		}

		// Filter Group
		$this->load->model('catalog/filter_group');

		$results = $this->model_catalog_filter_group->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $filter_group) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group_description` SET `filter_group_id` = '" . (int)$filter_group['filter_group_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($filter_group['name']) . "'");
		}

		// Information
		$this->load->model('catalog/information');

		$results = $this->model_catalog_information->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $information) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "information_description` SET `information_id` = '" . (int)$information['information_id'] . "', `language_id` = '" . (int)$language_id . "', `title` = '" . $this->db->escape($information['title']) . "', `description` = '" . $this->db->escape($information['description']) . "', `meta_title` = '" . $this->db->escape($information['meta_title']) . "', `meta_description` = '" . $this->db->escape($information['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($information['meta_keyword']) . "'");
		}

		// Length
		$this->load->model('localisation/length_class');

		$results = $this->model_localisation_length_class->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($query->rows as $length) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "length_class_description` SET `length_class_id` = '" . (int)$length['length_class_id'] . "', `language_id` = '" . (int)$language_id . "', `title` = '" . $this->db->escape($length['title']) . "', `unit` = '" . $this->db->escape($length['unit']) . "'");
		}

		// Option
		$this->load->model('catalog/option');

		$results = $this->model_catalog_option->getDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $option) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "option_description` SET `option_id` = '" . (int)$option['option_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($option['name']) . "'");
		}

		// Option Value
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_value_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		$results = $this->model_catalog_option->getValueDescriptionsByLanguageId($this->config->get('config_language_id'));

		foreach ($query->rows as $option_value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "option_value_description` SET `option_value_id` = '" . (int)$option_value['option_value_id'] . "', `language_id` = '" . (int)$language_id . "', `option_id` = '" . (int)$option_value['option_id'] . "', `name` = '" . $this->db->escape($option_value['name']) . "'");
		}

		// Order Status
		$this->load->model('localisation/order_status');

		$results = $this->model_
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $order_status) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_status` SET `order_status_id` = '" . (int)$order_status['order_status_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($order_status['name']) . "'");
		}


		// Product
		$this->load->model('catalog/product');

		$results = $this->model_
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $product) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "product_description` SET `product_id` = '" . (int)$product['product_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($product['name']) . "', `description` = '" . $this->db->escape($product['description']) . "', `tag` = '" . $this->db->escape($product['tag']) . "', `meta_title` = '" . $this->db->escape($product['meta_title']) . "', `meta_description` = '" . $this->db->escape($product['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($product['meta_keyword']) . "'");
		}


		// Product Attribute
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_attribute` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		$results = $this->model_

		foreach ($query->rows as $product_attribute) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "product_attribute` SET `product_id` = '" . (int)$product_attribute['product_id'] . "', `attribute_id` = '" . (int)$product_attribute['attribute_id'] . "', `language_id` = '" . (int)$language_id . "', `text` = '" . $this->db->escape($product_attribute['text']) . "'");
		}

		// Return Action
		$this->load->model('localisation/return_action');

		$results = $this->model_

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "return_action` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $return_action) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "return_action` SET `return_action_id` = '" . (int)$return_action['return_action_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($return_action['name']) . "'");
		}

		// Return Reason
		$this->load->model('localisation/return_reason');

		$results = $this->model_

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "return_reason` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $return_reason) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "return_reason` SET `return_reason_id` = '" . (int)$return_reason['return_reason_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($return_reason['name']) . "'");
		}

		// Return Status
		$this->load->model('localisation/return_status');

		$results = $this->model_

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "return_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $return_status) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "return_status` SET `return_status_id` = '" . (int)$return_status['return_status_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($return_status['name']) . "'");
		}

		// Stock Status
		$this->load->model('localisation/stock_status');

		$results = $this->model_localisation_stock_status->getDescriptionsByLanguageId($language_id);

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "stock_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $stock_status) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "stock_status` SET `stock_status_id` = '" . (int)$stock_status['stock_status_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($stock_status['name']) . "'");
		}



		// Voucher Theme
		$this->load->model('localisation/voucher_theme');

		$results = $this->model_localisation_voucher_theme->getDescriptionsByLanguageId($language_id);

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "voucher_theme_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $voucher_theme) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "voucher_theme_description` SET `voucher_theme_id` = '" . (int)$voucher_theme['voucher_theme_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($voucher_theme['name']) . "'");
		}



		// Weight Class
		$this->load->model('localisation/weight_class');

		$results = $this->model_localisation_weight_class->getDescriptionsByLanguageId($language_id);

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "weight_class_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $weight_class) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "weight_class_description` SET `weight_class_id` = '" . (int)$weight_class['weight_class_id'] . "', `language_id` = '" . (int)$language_id . "', `title` = '" . $this->db->escape($weight_class['title']) . "', `unit` = '" . $this->db->escape($weight_class['unit']) . "'");
		}



		// Subscription
		$this->load->model('localisation/subscription_status');

		$results = $this->model_localisation_subscription_status->getDescriptionsByLanguageId($language_id);

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $subscription) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_status` SET `subscription_status_id` = '" . (int)$subscription['subscription_status_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($subscription['name']) . "'");
		}

		// SEO URL
		$this->load->model('design/seo_url');

		$results = $this->model_


		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $seo_url) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$seo_url['store_id'] . "', `language_id` = '" . (int)$language_id . "', `key` = '" . $this->db->escape($seo_url['key']) . "', `value` = '" . $this->db->escape($seo_url['value']) . "', `keyword` = '" . $this->db->escape($seo_url['keyword']) . "', `sort_order` = '" . (int)$seo_url['sort_order'] . "'");
		}

		return $language_id;
	}

	/**
	 * Edit Language
	 *
	 * @param int                  $language_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function editLanguage(int $language_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "language` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `locale` = '" . $this->db->escape((string)$data['locale']) . "', `extension` = '" . $this->db->escape((string)$data['extension']) . "', `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "' WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('language');
	}

	/**
	 * Delete Language
	 *
	 * @param int $language_id
	 *
	 * @return void
	 */
	public function deleteLanguage(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "language` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('language');

		// Attribute
		$this->load->model('catalog/attribute');

		$this->model_catalog_attribute->deleteDescriptionsByLanguageId($language_id);

		// Attribute Group
		$this->load->model('catalog/attribute_group');

		$this->model_catalog_attribute_group->deleteDescriptionsByLanguageId($language_id);

		// Banner
		$this->load->model('design/banner');

		$this->model_design_banner->deleteDescriptionsByLanguageId($language_id);

		// Category
		$this->load->model('catalog/category');

		$this->model_catalog_category->deleteDescriptionsByLanguageId($language_id);

		// Customer Group
		$this->load->model('customer/customer_group');

		$this->model_catalog_customer_group->deleteDescriptionsByLanguageId($language_id);

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

		// Length
		$this->load->model('localisation/length_class');

		$this->model_localisation_length_class->deleteDescriptionsByLanguageId($language_id);

		// Option
		$this->load->model('catalog/option');

		$this->model_catalog_option->deleteDescriptionsByLanguageId($language_id);
		$this->model_catalog_option->deleteValueDescriptionsByLanguageId($language_id);

		// Order Status
		$this->load->model('localisation/order_status');

		$this->model_localisation_order_status->deleteDescriptionsByLanguageId($language_id);

		// Product
		$this->load->model('catalog/product');

		$this->model_catalog_product->deleteDescriptionsByLanguageId($language_id);
		$this->model_catalog_product->deleteAttributesByLanguageId($language_id);

		// Return Action
		$this->load->model('localisation/return_action');

		$this->model_localisation_return_action->deleteDescriptionsByLanguageId($language_id);

		// Return Reason
		$this->load->model('localisation/return_reason');

		$this->model_localisation_return_reason->deleteDescriptionsByLanguageId($language_id);

		// Return Status
		$this->load->model('localisation/return_status');

		$this->model_localisation_return_status->deleteDescriptionsByLanguageId($language_id);

		// Stock Status
		$this->load->model('localisation/stock_status');

		$this->model_localisation_stock_status->deleteDescriptionsByLanguageId($language_id);

		// Voucher Theme
		$this->load->model('localisation/voucher_theme');

		$this->model_localisation_voucher_theme->deleteDescriptionsByLanguageId($language_id);

		// Weight Class
		$this->load->model('localisation/weight_class');

		$this->model_localisation_weight_class->deleteDescriptionsByLanguageId($language_id);

		// Subscription Status
		$this->load->model('localisation/subscription_status');

		$this->model_localisation_subscription_status->deleteDescriptionsByLanguageId($language_id);

		// SEO URL
		$this->load->model('design/seo_url');

		$this->model_design_seo_url->deleteSeoUrlsByLanguageId($language_id);
	}

	/**
	 * Get Language
	 *
	 * @param int $language_id
	 *
	 * @return array<string, mixed>
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
	 * @param array<string, mixed> $data
	 *
	 * @return array<string, array<string, mixed>>
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

			$language_data[$result['code']] = [
				'language_id' => $result['language_id'],
				'name'        => $result['name'],
				'code'        => $result['code'],
				'image'       => $image . 'language/' . $result['code'] . '/' . $result['code'] . '.png',
				'locale'      => $result['locale'],
				'extension'   => $result['extension'],
				'sort_order'  => $result['sort_order'],
				'status'      => $result['status']
			];
		}

		return $language_data;
	}

	/**
	 * Get Languages By Extension
	 *
	 * @param string $extension
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getLanguagesByExtension(string $extension): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE `extension` = '" . $this->db->escape($extension) . "'");

		return $query->rows;
	}

	/**
	 * Get Total Languages
	 *
	 * @return int
	 */
	public function getTotalLanguages(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "language`");

		return (int)$query->row['total'];
	}
}
