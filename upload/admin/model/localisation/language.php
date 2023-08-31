<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Language
 *
 * @package Opencart\Admin\Model\Localisation
 */
class Language extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function addLanguage(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "language` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `locale` = '" . $this->db->escape((string)$data['locale']) . "', `extension` = '" . $this->db->escape((string)$data['extension']) . "', `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "'");

		$this->cache->delete('language');

		$language_id = $this->db->getLastId();

		// Attribute
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "attribute_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $attribute) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_description` SET `attribute_id` = '" . (int)$attribute['attribute_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($attribute['name']) . "'");
		}

		// Attribute Group
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "attribute_group_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $attribute_group) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_group_description` SET `attribute_group_id` = '" . (int)$attribute_group['attribute_group_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($attribute_group['name']) . "'");
		}

		$this->cache->delete('attribute');

		// Banner
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "banner_image` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $banner_image) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "banner_image` SET `banner_id` = '" . (int)$banner_image['banner_id'] . "', `language_id` = '" . (int)$language_id . "', `title` = '" . $this->db->escape($banner_image['title']) . "', `link` = '" . $this->db->escape($banner_image['link']) . "', `image` = '" . $this->db->escape($banner_image['image']) . "', `sort_order` = '" . (int)$banner_image['sort_order'] . "'");
		}

		$this->cache->delete('banner');

		// Category
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $category) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "category_description` SET `category_id` = '" . (int)$category['category_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($category['name']) . "', `description` = '" . $this->db->escape($category['description']) . "', `meta_title` = '" . $this->db->escape($category['meta_title']) . "', `meta_description` = '" . $this->db->escape($category['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($category['meta_keyword']) . "'");
		}

		// Customer Group
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_group_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $customer_group) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_group_description` SET `customer_group_id` = '" . (int)$customer_group['customer_group_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($customer_group['name']) . "', `description` = '" . $this->db->escape($customer_group['description']) . "'");
		}

		// Custom Field
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $custom_field) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "custom_field_description` SET `custom_field_id` = '" . (int)$custom_field['custom_field_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($custom_field['name']) . "'");
		}

		// Custom Field Value
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "custom_field_value_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $custom_field_value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "custom_field_value_description` SET `custom_field_value_id` = '" . (int)$custom_field_value['custom_field_value_id'] . "', `language_id` = '" . (int)$language_id . "', `custom_field_id` = '" . (int)$custom_field_value['custom_field_id'] . "', `name` = '" . $this->db->escape($custom_field_value['name']) . "'");
		}

		// Download
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "download_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $download) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "download_description` SET `download_id` = '" . (int)$download['download_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($download['name']) . "'");
		}

		// Filter
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $filter) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_description` SET `filter_id` = '" . (int)$filter['filter_id'] . "', `language_id` = '" . (int)$language_id . "', `filter_group_id` = '" . (int)$filter['filter_group_id'] . "', `name` = '" . $this->db->escape($filter['name']) . "'");
		}

		// Filter Group
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter_group_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $filter_group) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group_description` SET `filter_group_id` = '" . (int)$filter_group['filter_group_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($filter_group['name']) . "'");
		}

		// Information
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "information_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $information) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "information_description` SET `information_id` = '" . (int)$information['information_id'] . "', `language_id` = '" . (int)$language_id . "', `title` = '" . $this->db->escape($information['title']) . "', `description` = '" . $this->db->escape($information['description']) . "', `meta_title` = '" . $this->db->escape($information['meta_title']) . "', `meta_description` = '" . $this->db->escape($information['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($information['meta_keyword']) . "'");
		}

		$this->cache->delete('information');

		// Length
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "length_class_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $length) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "length_class_description` SET `length_class_id` = '" . (int)$length['length_class_id'] . "', `language_id` = '" . (int)$language_id . "', `title` = '" . $this->db->escape($length['title']) . "', `unit` = '" . $this->db->escape($length['unit']) . "'");
		}

		$this->cache->delete('length_class');

		// Option
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $option) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "option_description` SET `option_id` = '" . (int)$option['option_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($option['name']) . "'");
		}

		// Option Value
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_value_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $option_value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "option_value_description` SET `option_value_id` = '" . (int)$option_value['option_value_id'] . "', `language_id` = '" . (int)$language_id . "', `option_id` = '" . (int)$option_value['option_id'] . "', `name` = '" . $this->db->escape($option_value['name']) . "'");
		}

		// Order Status
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $order_status) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_status` SET `order_status_id` = '" . (int)$order_status['order_status_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($order_status['name']) . "'");
		}

		$this->cache->delete('order_status');

		// Product
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $product) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "product_description` SET `product_id` = '" . (int)$product['product_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($product['name']) . "', `description` = '" . $this->db->escape($product['description']) . "', `tag` = '" . $this->db->escape($product['tag']) . "', `meta_title` = '" . $this->db->escape($product['meta_title']) . "', `meta_description` = '" . $this->db->escape($product['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($product['meta_keyword']) . "'");
		}

		$this->cache->delete('product');

		// Product Attribute
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_attribute` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $product_attribute) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "product_attribute` SET `product_id` = '" . (int)$product_attribute['product_id'] . "', `attribute_id` = '" . (int)$product_attribute['attribute_id'] . "', `language_id` = '" . (int)$language_id . "', `text` = '" . $this->db->escape($product_attribute['text']) . "'");
		}

		// Return Action
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "return_action` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $return_action) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "return_action` SET `return_action_id` = '" . (int)$return_action['return_action_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($return_action['name']) . "'");
		}

		// Return Reason
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "return_reason` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $return_reason) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "return_reason` SET `return_reason_id` = '" . (int)$return_reason['return_reason_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($return_reason['name']) . "'");
		}

		// Return Status
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "return_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $return_status) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "return_status` SET `return_status_id` = '" . (int)$return_status['return_status_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($return_status['name']) . "'");
		}

		// Stock Status
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "stock_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $stock_status) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "stock_status` SET `stock_status_id` = '" . (int)$stock_status['stock_status_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($stock_status['name']) . "'");
		}

		$this->cache->delete('stock_status');

		// Voucher Theme
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "voucher_theme_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $voucher_theme) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "voucher_theme_description` SET `voucher_theme_id` = '" . (int)$voucher_theme['voucher_theme_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($voucher_theme['name']) . "'");
		}

		$this->cache->delete('voucher_theme');

		// Weight Class
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "weight_class_description` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $weight_class) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "weight_class_description` SET `weight_class_id` = '" . (int)$weight_class['weight_class_id'] . "', `language_id` = '" . (int)$language_id . "', `title` = '" . $this->db->escape($weight_class['title']) . "', `unit` = '" . $this->db->escape($weight_class['unit']) . "'");
		}

		$this->cache->delete('weight_class');

		// Subscription
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $subscription) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_status` SET `subscription_status_id` = '" . (int)$subscription['subscription_status_id'] . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($subscription['name']) . "'");
		}

		// SEO URL
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $seo_url) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$seo_url['store_id'] . "', `language_id` = '" . (int)$language_id . "', `key` = '" . $this->db->escape($seo_url['key']) . "', `value` = '" . $this->db->escape($seo_url['value']) . "', `keyword` = '" . $this->db->escape($seo_url['keyword']) . "', `sort_order` = '" . (int)$seo_url['sort_order'] . "'");
		}

		return $language_id;
	}

	/**
	 * @param int   $language_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editLanguage(int $language_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "language` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `locale` = '" . $this->db->escape((string)$data['locale']) . "', `extension` = '" . $this->db->escape((string)$data['extension']) . "', `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "' WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('language');
	}

	/**
	 * @param int $language_id
	 *
	 * @return void
	 */
	public function deleteLanguage(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "language` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('language');

		$this->db->query("DELETE FROM `" . DB_PREFIX . "attribute_description` WHERE `language_id` = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "attribute_group_description` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('attribute');

		$this->db->query("DELETE FROM `" . DB_PREFIX . "banner_image` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('banner');

		$this->db->query("DELETE FROM `" . DB_PREFIX . "category_description` WHERE `language_id` = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_group_description` WHERE `language_id` = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "custom_field_description` WHERE `language_id` = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "custom_field_value_description` WHERE `language_id` = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "download_description` WHERE `language_id` = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_description` WHERE `language_id` = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_group_description` WHERE `language_id` = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "information_description` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('information');

		$this->db->query("DELETE FROM `" . DB_PREFIX . "length_class_description` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('length_class');

		$this->db->query("DELETE FROM `" . DB_PREFIX . "option_description` WHERE `language_id` = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "option_value_description` WHERE `language_id` = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_status` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('order_status');

		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_description` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('product');

		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_attribute` WHERE `language_id` = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return_action` WHERE `language_id` = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return_reason` WHERE `language_id` = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return_status` WHERE `language_id` = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "stock_status` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('stock_status');

		$this->db->query("DELETE FROM `" . DB_PREFIX . "voucher_theme_description` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('voucher_theme');

		$this->db->query("DELETE FROM `" . DB_PREFIX . "weight_class_description` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('weight_class');

		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_status` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('subscription_status');

		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * @param int $language_id
	 *
	 * @return array
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
	 * @param string $code
	 *
	 * @return array
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
	 * @param array $data
	 *
	 * @return array
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

		$results = (array)$this->cache->get('language.' . md5($sql));

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
	 * @return int
	 */
	public function getTotalLanguages(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "language`");

		return (int)$query->row['total'];
	}
}
