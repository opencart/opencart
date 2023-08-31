<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Product
 *
 * @package Opencart\Admin\Model\Catalog
 */
class Product extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function addProduct(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "product` SET `master_id` = '" . (int)$data['master_id'] . "', `model` = '" . $this->db->escape((string)$data['model']) . "', `sku` = '" . $this->db->escape((string)$data['sku']) . "', `upc` = '" . $this->db->escape((string)$data['upc']) . "', `ean` = '" . $this->db->escape((string)$data['ean']) . "', `jan` = '" . $this->db->escape((string)$data['jan']) . "', `isbn` = '" . $this->db->escape((string)$data['isbn']) . "', `mpn` = '" . $this->db->escape((string)$data['mpn']) . "', `location` = '" . $this->db->escape((string)$data['location']) . "', `variant` = '" . $this->db->escape(!empty($data['variant']) ? json_encode($data['variant']) : '') . "', `override` = '" . $this->db->escape(!empty($data['override']) ? json_encode($data['override']) : '') . "', `quantity` = '" . (int)$data['quantity'] . "', `minimum` = '" . (int)$data['minimum'] . "', `subtract` = '" . (isset($data['subtract']) ? (bool)$data['subtract'] : 0) . "', `stock_status_id` = '" . (int)$data['stock_status_id'] . "', `date_available` = '" . $this->db->escape((string)$data['date_available']) . "', `manufacturer_id` = '" . (int)$data['manufacturer_id'] . "', `shipping` = '" . (isset($data['shipping']) ? (bool)$data['shipping'] : 0) . "', `price` = '" . (float)$data['price'] . "', `points` = '" . (int)$data['points'] . "', `weight` = '" . (float)$data['weight'] . "', `weight_class_id` = '" . (int)$data['weight_class_id'] . "', `length` = '" . (float)$data['length'] . "', `width` = '" . (float)$data['width'] . "', `height` = '" . (float)$data['height'] . "', `length_class_id` = '" . (int)$data['length_class_id'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "', `tax_class_id` = '" . (int)$data['tax_class_id'] . "', `sort_order` = '" . (int)$data['sort_order'] . "', `date_added` = NOW(), `date_modified` = NOW()");

		$product_id = $this->db->getLastId();

		if ($data['image']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `image` = '" . $this->db->escape((string)$data['image']) . "' WHERE `product_id` = '" . (int)$product_id . "'");
		}

		// Description
		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "product_description` SET `product_id` = '" . (int)$product_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "', `description` = '" . $this->db->escape($value['description']) . "', `tag` = '" . $this->db->escape($value['tag']) . "', `meta_title` = '" . $this->db->escape($value['meta_title']) . "', `meta_description` = '" . $this->db->escape($value['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// Categories
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_category` SET `product_id` = '" . (int)$product_id . "', `category_id` = '" . (int)$category_id . "'");
			}
		}

		// Filters
		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_filter` SET `product_id` = '" . (int)$product_id . "', `filter_id` = '" . (int)$filter_id . "'");
			}
		}

		// Stores
		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_store` SET `product_id` = '" . (int)$product_id . "', `store_id` = '" . (int)$store_id . "'");
			}
		}

		// Downloads
		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_download` SET `product_id` = '" . (int)$product_id . "', `download_id` = '" . (int)$download_id . "'");
			}
		}

		// Related
		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM `" . DB_PREFIX . "product_related` WHERE `product_id` = '" . (int)$product_id . "' AND `related_id` = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_related` SET `product_id` = '" . (int)$product_id . "', `related_id` = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM `" . DB_PREFIX . "product_related` WHERE `product_id` = '" . (int)$related_id . "' AND `related_id` = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_related` SET `product_id` = '" . (int)$related_id . "', `related_id` = '" . (int)$product_id . "'");
			}
		}

		// Attributes
		if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					// Removes duplicates
					$this->db->query("DELETE FROM `" . DB_PREFIX . "product_attribute` WHERE `product_id` = '" . (int)$product_id . "' AND `attribute_id` = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "product_attribute` SET `product_id` = '" . (int)$product_id . "', `attribute_id` = '" . (int)$product_attribute['attribute_id'] . "', `language_id` = '" . (int)$language_id . "', `text` = '" . $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}

		// Options
		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['product_option_value'])) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "product_option` SET `product_id` = '" . (int)$product_id . "', `option_id` = '" . (int)$product_option['option_id'] . "', `required` = '" . (int)$product_option['required'] . "'");

						$product_option_id = $this->db->getLastId();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO `" . DB_PREFIX . "product_option_value` SET `product_option_id` = '" . (int)$product_option_id . "', `product_id` = '" . (int)$product_id . "', `option_id` = '" . (int)$product_option['option_id'] . "', `option_value_id` = '" . (int)$product_option_value['option_value_id'] . "', `quantity` = '" . (int)$product_option_value['quantity'] . "', `subtract` = '" . (int)$product_option_value['subtract'] . "', `price` = '" . (float)$product_option_value['price'] . "', `price_prefix` = '" . $this->db->escape($product_option_value['price_prefix']) . "', `points` = '" . (int)$product_option_value['points'] . "', `points_prefix` = '" . $this->db->escape($product_option_value['points_prefix']) . "', `weight` = '" . (float)$product_option_value['weight'] . "', `weight_prefix` = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "product_option` SET `product_id` = '" . (int)$product_id . "', `option_id` = '" . (int)$product_option['option_id'] . "', `value` = '" . $this->db->escape($product_option['value']) . "', `required` = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		// Subscription
		if (isset($data['product_subscription'])) {
			foreach ($data['product_subscription'] as $product_subscription) {
				$query = $this->db->query("SELECT `product_id` FROM `" . DB_PREFIX . "product_subscription` WHERE `product_id` = '" . (int)$product_id . "' AND `customer_group_id` = '" . (int)$product_subscription['customer_group_id'] . "' AND `subscription_plan_id` = '" . (int)$product_subscription['subscription_plan_id'] . "'");

				if (!$query->num_rows) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "product_subscription` SET `product_id` = '" . (int)$product_id . "', `customer_group_id` = '" . (int)$product_subscription['customer_group_id'] . "', `subscription_plan_id` = '" . (int)$product_subscription['subscription_plan_id'] . "', `trial_price` = '" . (float)$product_subscription['trial_price'] . "', `price` = '" . (float)$product_subscription['price'] . "'");
				}
			}
		}

		// Discounts
		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_discount` SET `product_id` = '" . (int)$product_id . "', `customer_group_id` = '" . (int)$product_discount['customer_group_id'] . "', `quantity` = '" . (int)$product_discount['quantity'] . "', `priority` = '" . (int)$product_discount['priority'] . "', `price` = '" . (float)$product_discount['price'] . "', `date_start` = '" . $this->db->escape($product_discount['date_start']) . "', `date_end` = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		// Specials
		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_special` SET `product_id` = '" . (int)$product_id . "', `customer_group_id` = '" . (int)$product_special['customer_group_id'] . "', `priority` = '" . (int)$product_special['priority'] . "', `price` = '" . (float)$product_special['price'] . "', `date_start` = '" . $this->db->escape($product_special['date_start']) . "', `date_end` = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		// Images
		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_image` SET `product_id` = '" . (int)$product_id . "', `image` = '" . $this->db->escape($product_image['image']) . "', `sort_order` = '" . (int)$product_image['sort_order'] . "'");
			}
		}

		// Reward
		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
				if ((int)$product_reward['points'] > 0) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "product_reward` SET `product_id` = '" . (int)$product_id . "', `customer_group_id` = '" . (int)$customer_group_id . "', `points` = '" . (int)$product_reward['points'] . "'");
				}
			}
		}

		// SEO URL
		if (isset($data['product_seo_url'])) {
			foreach ($data['product_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'product_id', `value` = '" . (int)$product_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");
				}
			}
		}

		// Layout
		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_layout` SET `product_id` = '" . (int)$product_id . "', `store_id` = '" . (int)$store_id . "', `layout_id` = '" . (int)$layout_id . "'");
			}
		}

		$this->cache->delete('product');

		return $product_id;
	}

	/**
	 * @param int   $product_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editProduct(int $product_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `model` = '" . $this->db->escape((string)$data['model']) . "', `sku` = '" . $this->db->escape((string)$data['sku']) . "', `upc` = '" . $this->db->escape((string)$data['upc']) . "', `ean` = '" . $this->db->escape((string)$data['ean']) . "', `jan` = '" . $this->db->escape((string)$data['jan']) . "', `isbn` = '" . $this->db->escape((string)$data['isbn']) . "', `mpn` = '" . $this->db->escape((string)$data['mpn']) . "', `location` = '" . $this->db->escape((string)$data['location']) . "', `variant` = '" . $this->db->escape(!empty($data['variant']) ? json_encode($data['variant']) : '') . "', `override` = '" . $this->db->escape(!empty($data['override']) ? json_encode($data['override']) : '') . "', `quantity` = '" . (int)$data['quantity'] . "', `minimum` = '" . (int)$data['minimum'] . "', `subtract` = '" . (isset($data['subtract']) ? (bool)$data['subtract'] : 0) . "', `stock_status_id` = '" . (int)$data['stock_status_id'] . "', `date_available` = '" . $this->db->escape((string)$data['date_available']) . "', `manufacturer_id` = '" . (int)$data['manufacturer_id'] . "', `shipping` = '" . (isset($data['shipping']) ? (bool)$data['shipping'] : 0) . "', `price` = '" . (float)$data['price'] . "', `points` = '" . (int)$data['points'] . "', `weight` = '" . (float)$data['weight'] . "', `weight_class_id` = '" . (int)$data['weight_class_id'] . "', `length` = '" . (float)$data['length'] . "', `width` = '" . (float)$data['width'] . "', `height` = '" . (float)$data['height'] . "', `length_class_id` = '" . (int)$data['length_class_id'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "', `tax_class_id` = '" . (int)$data['tax_class_id'] . "', `sort_order` = '" . (int)$data['sort_order'] . "', `date_modified` = NOW() WHERE `product_id` = '" . (int)$product_id . "'");

		if ($data['image']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `image` = '" . $this->db->escape((string)$data['image']) . "' WHERE `product_id` = '" . (int)$product_id . "'");
		}

		// Description
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_description` WHERE `product_id` = '" . (int)$product_id . "'");

		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "product_description` SET `product_id` = '" . (int)$product_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "', `description` = '" . $this->db->escape($value['description']) . "', `tag` = '" . $this->db->escape($value['tag']) . "', `meta_title` = '" . $this->db->escape($value['meta_title']) . "', `meta_description` = '" . $this->db->escape($value['meta_description']) . "', `meta_keyword` = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// Categories
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int)$product_id . "'");

		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_category` SET `product_id` = '" . (int)$product_id . "', `category_id` = '" . (int)$category_id . "'");
			}
		}

		// Filters
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_filter` WHERE `product_id` = '" . (int)$product_id . "'");

		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_filter` SET `product_id` = '" . (int)$product_id . "', `filter_id` = '" . (int)$filter_id . "'");
			}
		}

		// Stores
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_store` WHERE `product_id` = '" . (int)$product_id . "'");

		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_store` SET `product_id` = '" . (int)$product_id . "', `store_id` = '" . (int)$store_id . "'");
			}
		}

		// Downloads
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_download` WHERE `product_id` = '" . (int)$product_id . "'");

		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_download` SET `product_id` = '" . (int)$product_id . "', `download_id` = '" . (int)$download_id . "'");
			}
		}

		// Related
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_related` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_related` WHERE `related_id` = '" . (int)$product_id . "'");

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM `" . DB_PREFIX . "product_related` WHERE `product_id` = '" . (int)$product_id . "' AND `related_id` = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_related` SET `product_id` = '" . (int)$product_id . "', `related_id` = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM `" . DB_PREFIX . "product_related` WHERE `product_id` = '" . (int)$related_id . "' AND `related_id` = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_related` SET `product_id` = '" . (int)$related_id . "', `related_id` = '" . (int)$product_id . "'");
			}
		}

		// Attributes
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_attribute` WHERE `product_id` = '" . (int)$product_id . "'");

		if (!empty($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					// Removes duplicates
					$this->db->query("DELETE FROM `" . DB_PREFIX . "product_attribute` WHERE `product_id` = '" . (int)$product_id . "' AND `attribute_id` = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "product_attribute` SET `product_id` = '" . (int)$product_id . "', `attribute_id` = '" . (int)$product_attribute['attribute_id'] . "', `language_id` = '" . (int)$language_id . "', `text` = '" . $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}

		// Options
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_option` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_option_value` WHERE `product_id` = '" . (int)$product_id . "'");

		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['product_option_value'])) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "product_option` SET `product_option_id` = '" . (int)$product_option['product_option_id'] . "', `product_id` = '" . (int)$product_id . "', `option_id` = '" . (int)$product_option['option_id'] . "', `required` = '" . (int)$product_option['required'] . "'");

						$product_option_id = $this->db->getLastId();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO `" . DB_PREFIX . "product_option_value` SET `product_option_value_id` = '" . (int)$product_option_value['product_option_value_id'] . "', `product_option_id` = '" . (int)$product_option_id . "', `product_id` = '" . (int)$product_id . "', `option_id` = '" . (int)$product_option['option_id'] . "', `option_value_id` = '" . (int)$product_option_value['option_value_id'] . "', `quantity` = '" . (int)$product_option_value['quantity'] . "', `subtract` = '" . (int)$product_option_value['subtract'] . "', `price` = '" . (float)$product_option_value['price'] . "', `price_prefix` = '" . $this->db->escape($product_option_value['price_prefix']) . "', `points` = '" . (int)$product_option_value['points'] . "', `points_prefix` = '" . $this->db->escape($product_option_value['points_prefix']) . "', `weight` = '" . (float)$product_option_value['weight'] . "', `weight_prefix` = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "product_option` SET `product_option_id` = '" . (int)$product_option['product_option_id'] . "', `product_id` = '" . (int)$product_id . "', `option_id` = '" . (int)$product_option['option_id'] . "', `value` = '" . $this->db->escape($product_option['value']) . "', `required` = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		// Subscription
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_subscription` WHERE `product_id` = '" . (int)$product_id . "'");

		if (isset($data['product_subscription'])) {
			foreach ($data['product_subscription'] as $product_subscription) {
				$query = $this->db->query("SELECT `product_id` FROM `" . DB_PREFIX . "product_subscription` WHERE `product_id` = '" . (int)$product_id . "' AND `customer_group_id` = '" . (int)$product_subscription['customer_group_id'] . "' AND `subscription_plan_id` = '" . (int)$product_subscription['subscription_plan_id'] . "'");

				if (!$query->num_rows) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "product_subscription` SET `product_id` = '" . (int)$product_id . "', `customer_group_id` = '" . (int)$product_subscription['customer_group_id'] . "', `subscription_plan_id` = '" . (int)$product_subscription['subscription_plan_id'] . "', `trial_price` = '" . (float)$product_subscription['trial_price'] . "', `price` = '" . (float)$product_subscription['price'] . "'");
				}
			}
		}

		// Discounts
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_discount` WHERE `product_id` = '" . (int)$product_id . "'");

		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_discount` SET `product_id` = '" . (int)$product_id . "', `customer_group_id` = '" . (int)$product_discount['customer_group_id'] . "', `quantity` = '" . (int)$product_discount['quantity'] . "', `priority` = '" . (int)$product_discount['priority'] . "', `price` = '" . (float)$product_discount['price'] . "', `date_start` = '" . $this->db->escape($product_discount['date_start']) . "', `date_end` = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		// Specials
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_special` WHERE `product_id` = '" . (int)$product_id . "'");

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_special` SET `product_id` = '" . (int)$product_id . "', `customer_group_id` = '" . (int)$product_special['customer_group_id'] . "', `priority` = '" . (int)$product_special['priority'] . "', `price` = '" . (float)$product_special['price'] . "', `date_start` = '" . $this->db->escape($product_special['date_start']) . "', `date_end` = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		// Images
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_image` WHERE `product_id` = '" . (int)$product_id . "'");

		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_image` SET `product_id` = '" . (int)$product_id . "', `image` = '" . $this->db->escape($product_image['image']) . "', `sort_order` = '" . (int)$product_image['sort_order'] . "'");
			}
		}

		// Rewards
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_reward` WHERE `product_id` = '" . (int)$product_id . "'");

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $value) {
				if ((int)$value['points'] > 0) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "product_reward` SET `product_id` = '" . (int)$product_id . "', `customer_group_id` = '" . (int)$customer_group_id . "', `points` = '" . (int)$value['points'] . "'");
				}
			}
		}

		// SEO URL
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'product_id' AND `value` = '" . (int)$product_id . "'");

		if (isset($data['product_seo_url'])) {
			foreach ($data['product_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = 'product_id', `value` = '" . (int)$product_id . "', `keyword` = '" . $this->db->escape($keyword) . "'");
				}
			}
		}

		// Layout
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_layout` WHERE `product_id` = '" . (int)$product_id . "'");

		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_layout` SET `product_id` = '" . (int)$product_id . "', `store_id` = '" . (int)$store_id . "', `layout_id` = '" . (int)$layout_id . "'");
			}
		}

		$this->cache->delete('product');
	}

	/**
	 * @param int $product_id
	 *
	 * @return void
	 */
	public function copyProduct(int $product_id): void {
		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			$product_data = $product_info;

			$product_data['sku'] = '';
			$product_data['upc'] = '';
			$product_data['status'] = '0';
			
			$product_data['variant'] = json_decode($product_info['variant'], true);
			$product_data['override'] = json_decode($product_info['override'], true);

			$product_data['product_attribute'] = $this->model_catalog_product->getAttributes($product_id);
			$product_data['product_category'] = $this->model_catalog_product->getCategories($product_id);
			$product_data['product_description'] = $this->model_catalog_product->getDescriptions($product_id);
			$product_data['product_discount'] = $this->model_catalog_product->getDiscounts($product_id);
			$product_data['product_download'] = $this->model_catalog_product->getDownloads($product_id);
			$product_data['product_filter'] = $this->model_catalog_product->getFilters($product_id);
			$product_data['product_image'] = $this->model_catalog_product->getImages($product_id);
			$product_data['product_layout'] = $this->model_catalog_product->getLayouts($product_id);
			$product_data['product_option'] = $this->model_catalog_product->getOptions($product_id);
			$product_data['product_subscription'] = $this->model_catalog_product->getSubscriptions($product_id);
			$product_data['product_related'] = $this->model_catalog_product->getRelated($product_id);
			$product_data['product_reward'] = $this->model_catalog_product->getRewards($product_id);
			$product_data['product_special'] = $this->model_catalog_product->getSpecials($product_id);
			$product_data['product_store'] = $this->model_catalog_product->getStores($product_id);

			$this->model_catalog_product->addProduct($product_data);
		}
	}

	/**
	 * @param int $product_id
	 *
	 * @return void
	 */
	public function deleteProduct(int $product_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_attribute` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_description` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_discount` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_filter` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_image` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_option` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_option_value` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_subscription` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_related` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_related` WHERE `related_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_report` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_reward` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_special` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_download` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_layout` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_store` WHERE `product_id` = '" . (int)$product_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "review` WHERE `product_id` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'product_id' AND `value` = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "coupon_product` WHERE `product_id` = '" . (int)$product_id . "'");

		$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `master_id` = '0' WHERE `master_id` = '" . (int)$product_id . "'");

		$this->cache->delete('product');
	}

	/**
	 * @param int   $master_id
	 * @param array $data
	 *
	 * @return int
	 */
	public function addVariant(int $master_id, array $data): int {
		$product_data = [];

		// Use master values to override the values
		$master_info = $this->model_catalog_product->getProduct($master_id);

		if ($master_info) {
			// We use the override to override the master product values
			if (isset($data['override'])) {
				$override = (array)$data['override'];
			} else {
				$override = [];
			}

			$ignore = [
				'product_id',
				'master_id',
				'quantity',
				'override',
				'variant'
			];

			foreach ($master_info as $key => $value) {
				// So if key not in override or ignore list we replace with master value
				if (!array_key_exists($key, $override) && !in_array($key, $ignore)) {
					$product_data[$key] = $value;
				}
			}

			// Descriptions
			$product_descriptions = $this->model_catalog_product->getDescriptions($master_id);

			foreach ($product_descriptions as $language_id => $product_description) {
				foreach ($product_description as $key => $value) {
					// If an override has been found, we use the POST data values
					if (!isset($override['product_description'][$language_id][$key])) {
						$product_data['product_description'][$language_id][$key] = $value;
					}
				}
			}

			// Attributes
			if (!isset($override['product_attribute'])) {
				$product_data['product_attribute'] = $this->model_catalog_product->getAttributes($master_id);
			}

			// Category
			if (!isset($override['product_category'])) {
				$product_data['product_category'] = $this->model_catalog_product->getCategories($master_id);
			}

			// Discounts
			if (!isset($override['product_discount'])) {
				$product_data['product_discount'] = $this->model_catalog_product->getDiscounts($master_id);
			}

			// Downloads
			if (!isset($override['product_download'])) {
				$product_data['product_download'] = $this->model_catalog_product->getDownloads($master_id);
			}

			// Filters
			if (!isset($override['product_filter'])) {
				$product_data['product_filter'] = $this->model_catalog_product->getFilters($master_id);
			}

			// Images
			if (!isset($override['product_image'])) {
				$product_data['product_image'] = $this->model_catalog_product->getImages($master_id);
			}

			// Layouts
			if (!isset($override['product_layout'])) {
				$product_data['product_layout'] = $this->model_catalog_product->getLayouts($master_id);
			}

			// Options
			// product_option should not be used if variant product

			// Subscriptions
			if (!isset($override['product_subscription'])) {
				$product_data['product_subscription'] = $this->model_catalog_product->getSubscriptions($master_id);
			}

			// Related
			if (!isset($override['product_related'])) {
				$product_data['product_related'] = $this->model_catalog_product->getRelated($master_id);
			}

			// Rewards
			if (!isset($override['product_reward'])) {
				$product_data['product_reward'] = $this->model_catalog_product->getRewards($master_id);
			}

			// SEO
			// product_seo table is not overwritten because that needs to have unique seo keywords for every product

			// Specials
			if (!isset($override['product_special'])) {
				$product_data['product_special'] = $this->model_catalog_product->getSpecials($master_id);
			}

			// Stores
			if (!isset($override['product_store'])) {
				$product_data['product_store'] = $this->model_catalog_product->getStores($master_id);
			}
		}

		// If override set the POST data values
		foreach ($data as $key => $value) {
			if (!isset($product_data[$key])) {
				$product_data[$key] = $value;
			}
		}

		// Product Description
		if (isset($data['product_description'])) {
			foreach ($data['product_description'] as $language_id => $product_description) {
				foreach ($product_description as $key => $value) {
					if (!isset($product_data['product_description'][$language_id][$key])) {
						$product_data['product_description'][$language_id][$key] = $value;
					}
				}
			}
		}

		// Product add with master product overridden values
		return $this->model_catalog_product->addProduct($product_data);
	}

	/**
	 * @param int   $master_id
	 * @param int   $product_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editVariant(int $master_id, int $product_id, array $data): void {
		$product_data = [];

		// Use master values to override the values
		$master_info = $this->model_catalog_product->getProduct($master_id);

		if ($master_info) {
			// We use the override to override the master product values
			if (isset($data['override'])) {
				$override = (array)$data['override'];
			} else {
				$override = [];
			}

			$ignore = [
				'product_id',
				'master_id',
				'quantity',
				'override',
				'variant'
			];

			foreach ($master_info as $key => $value) {
				// So if key not in override or ignore list we replace with master value
				if (!array_key_exists($key, $override) && !in_array($key, $ignore)) {
					$product_data[$key] = $value;
				}
			}

			// Description
			$product_descriptions = $this->model_catalog_product->getDescriptions($master_id);

			foreach ($product_descriptions as $language_id => $product_description) {
				foreach ($product_description as $key => $value) {
					if (!isset($override['product_description'][$language_id][$key])) {
						$product_data['product_description'][$language_id][$key] = $value;
					}
				}
			}

			// Attributes
			if (!isset($override['product_attribute'])) {
				$product_data['product_attribute'] = $this->model_catalog_product->getAttributes($master_id);
			}

			// Category
			if (!isset($override['product_category'])) {
				$product_data['product_category'] = $this->model_catalog_product->getCategories($master_id);
			}

			// Discounts
			if (!isset($override['product_discount'])) {
				$product_data['product_discount'] = $this->model_catalog_product->getDiscounts($master_id);
			}

			// Downloads
			if (!isset($override['product_download'])) {
				$product_data['product_download'] = $this->model_catalog_product->getDownloads($master_id);
			}

			// Filters
			if (!isset($override['product_filter'])) {
				$product_data['product_filter'] = $this->model_catalog_product->getFilters($master_id);
			}

			// Images
			if (!isset($override['product_image'])) {
				$product_data['product_image'] = $this->model_catalog_product->getImages($master_id);
			}

			// Layouts
			if (!isset($override['product_layout'])) {
				$product_data['product_layout'] = $this->model_catalog_product->getLayouts($master_id);
			}

			// Options
			// product_option should not be used if variant product

			// Subscription
			if (!isset($override['product_subscription'])) {
				$product_data['product_subscription'] = $this->model_catalog_product->getSubscriptions($master_id);
			}

			// Related
			if (!isset($override['product_related'])) {
				$product_data['product_related'] = $this->model_catalog_product->getRelated($master_id);
			}

			// Rewards
			if (!isset($override['product_reward'])) {
				$product_data['product_reward'] = $this->model_catalog_product->getRewards($master_id);
			}

			// SEO
			// product_seo table is not overwritten because that needs to have unique seo keywords for every product

			// Specials
			if (!isset($override['product_special'])) {
				$product_data['product_special'] = $this->model_catalog_product->getSpecials($master_id);
			}

			// Stores
			if (!isset($override['product_store'])) {
				$product_data['product_store'] = $this->model_catalog_product->getStores($master_id);
			}
		}

		// If override set the POST data values
		foreach ($data as $key => $value) {
			if (!isset($product_data[$key])) {
				$product_data[$key] = $value;
			}
		}

		// Product Description
		if (isset($data['product_description'])) {
			foreach ($data['product_description'] as $language_id => $product_description) {
				foreach ($product_description as $key => $value) {
					if (!isset($product_data['product_description'][$language_id][$key])) {
						$product_data['product_description'][$language_id][$key] = $value;
					}
				}
			}
		}

		// Override the variant product data with the master product values
		$this->model_catalog_product->editProduct($product_id, $product_data);
	}

	/**
	 * @param int   $master_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editVariants(int $master_id, array $data): void {
		// product_option should not be passed to product variants
		unset($data['product_option']);

		// If product is master update variants
		$products = $this->model_catalog_product->getProducts(['filter_master_id' => $master_id]);

		foreach ($products as $product) {
			$product_data = [];

			// We need to convert JSON strings back into an array so they can be re-encoded to a string to go back into the database.
			$product['override'] = (array)json_decode($product['override'], true);
			$product['variant'] = (array)json_decode($product['variant'], true);

			// We use the override to override the master product values
			if ($product['override']) {
				$override = $product['override'];
			} else {
				$override = [];
			}

			$replace = [
				'product_id',
				'master_id',
				'quantity',
				'override',
				'variant'
			];

			// Now we want to
			foreach ($product as $key => $value) {
				// So if key not in override or ignore list we replace with master value
				if (array_key_exists($key, $override) || in_array($key, $replace)) {
					$product_data[$key] = $value;
				}
			}

			// Descriptions
			$product_descriptions = $this->model_catalog_product->getDescriptions($product['product_id']);

			foreach ($product_descriptions as $language_id => $product_description) {
				foreach ($product_description as $key => $value) {
					// If override set use the POST data values
					if (isset($override['product_description'][$language_id][$key])) {
						$product_data['product_description'][$language_id][$key] = $value;
					}
				}
			}

			// Attributes
			if (isset($override['product_attribute'])) {
				$product_data['product_attribute'] = $this->model_catalog_product->getAttributes($product['product_id']);
			}

			// Category
			if (isset($override['product_category'])) {
				$product_data['product_category'] = $this->model_catalog_product->getCategories($product['product_id']);
			}

			// Discounts
			if (isset($override['product_discount'])) {
				$product_data['product_discount'] = $this->model_catalog_product->getDiscounts($product['product_id']);
			}

			// Downloads
			if (isset($override['product_download'])) {
				$product_data['product_download'] = $this->model_catalog_product->getDownloads($product['product_id']);
			}

			// Filters
			if (isset($override['product_filter'])) {
				$product_data['product_filter'] = $this->model_catalog_product->getFilters($product['product_id']);
			}

			// Images
			if (isset($override['product_image'])) {
				$product_data['product_image'] = $this->model_catalog_product->getImages($product['product_id']);
			}

			// Layouts
			if (isset($override['product_layout'])) {
				$product_data['product_layout'] = $this->model_catalog_product->getLayouts($product['product_id']);
			}

			// Subscription
			if (isset($override['product_subscription'])) {
				$product_data['product_subscription'] = $this->model_catalog_product->getSubscriptions($product['product_id']);
			}

			// Related
			if (isset($override['product_related'])) {
				$product_data['product_related'] = $this->model_catalog_product->getRelated($product['product_id']);
			}

			// Rewards
			if (isset($override['product_reward'])) {
				$product_data['product_reward'] = $this->model_catalog_product->getRewards($product['product_id']);
			}

			// SEO
			$product_data['product_seo_url'] = $this->model_catalog_product->getSeoUrls($product['product_id']);

			// Specials
			if (isset($override['product_special'])) {
				$product_data['product_special'] = $this->model_catalog_product->getSpecials($product['product_id']);
			}

			// Stores
			if (isset($override['product_store'])) {
				$product_data['product_store'] = $this->model_catalog_product->getStores($product['product_id']);
			}

			// If override set the POST data values
			foreach ($data as $key => $value) {
				if (!isset($product_data[$key])) {
					$product_data[$key] = $value;
				}
			}

			// Descriptions
			if (isset($data['product_description'])) {
				foreach ($data['product_description'] as $language_id => $product_description) {
					foreach ($product_description as $key => $value) {
						// If override set use the POST data values
						if (!isset($product_data['product_description'][$language_id][$key])) {
							$product_data['product_description'][$language_id][$key] = $value;
						}
					}
				}
			}

			$this->model_catalog_product->editProduct($product['product_id'], $product_data);
		}
	}

	/**
	 * @param int $product_id
	 * @param int $rating
	 *
	 * @return void
	 */
	public function editRating(int $product_id, int $rating): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `rating` = '" . (int)$rating . "', `date_modified` = NOW() WHERE `product_id` = '" . (int)$product_id . "'");
	}

	/**
	 * @param int $product_id
	 *
	 * @return array
	 */
	public function getProduct(int $product_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.`product_id` = pd.`product_id`) WHERE p.`product_id` = '" . (int)$product_id . "' AND pd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getProducts(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.`product_id` = pd.`product_id`) WHERE pd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_master_id'])) {
			$sql .= " AND p.`master_id` = '" . (int)$data['filter_master_id'] . "'";
		}

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.`name` LIKE '" . $this->db->escape((string)$data['filter_name'] . '%') . "'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.`model` LIKE '" . $this->db->escape((string)$data['filter_model'] . '%') . "'";
		}

		if (!empty($data['filter_price'])) {
			$sql .= " AND p.`price` LIKE '" . $this->db->escape((string)$data['filter_price'] . '%') . "'";
		}

		if (isset($data['filter_quantity']) && $data['filter_quantity'] !== '') {
			$sql .= " AND p.`quantity` = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND p.`status` = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY p.`product_id`";

		$sort_data = [
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.`name`";
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

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * @param int $product_id
	 *
	 * @return array
	 */
	public function getDescriptions(int $product_id): array {
		$product_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_description` WHERE `product_id` = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = [
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			];
		}

		return $product_description_data;
	}

	/**
	 * @param int $product_id
	 *
	 * @return array
	 */
	public function getCategories(int $product_id): array {
		$product_category_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}

	/**
	 * @param int $product_id
	 *
	 * @return array
	 */
	public function getFilters(int $product_id): array {
		$product_filter_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_filter` WHERE `product_id` = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_filter_data[] = $result['filter_id'];
		}

		return $product_filter_data;
	}

	/**
	 * @param int $product_id
	 *
	 * @return array
	 */
	public function getAttributes(int $product_id): array {
		$product_attribute_data = [];

		$product_attribute_query = $this->db->query("SELECT attribute_id FROM `" . DB_PREFIX . "product_attribute` WHERE `product_id` = '" . (int)$product_id . "' GROUP BY `attribute_id`");

		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = [];

			$product_attribute_description_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_attribute` WHERE `product_id` = '" . (int)$product_id . "' AND `attribute_id` = '" . (int)$product_attribute['attribute_id'] . "'");

			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
				$product_attribute_description_data[$product_attribute_description['language_id']] = ['text' => $product_attribute_description['text']];
			}

			$product_attribute_data[] = [
				'attribute_id'                  => $product_attribute['attribute_id'],
				'product_attribute_description' => $product_attribute_description_data
			];
		}

		return $product_attribute_data;
	}

	/**
	 * @param int $product_id
	 *
	 * @return array
	 */
	public function getOptions(int $product_id): array {
		$product_option_data = [];

		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.`option_id` = o.`option_id`) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.`option_id` = od.`option_id`) WHERE po.`product_id` = '" . (int)$product_id . "' AND od.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.`sort_order` ASC");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = [];

			$product_option_value_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option_value` pov LEFT JOIN `" . DB_PREFIX . "option_value` ov ON (pov.`option_value_id` = ov.`option_value_id`) WHERE pov.`product_option_id` = '" . (int)$product_option['product_option_id'] . "' ORDER BY ov.`sort_order` ASC");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = [
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'option_value_id'         => $product_option_value['option_value_id'],
					'quantity'                => $product_option_value['quantity'],
					'subtract'                => $product_option_value['subtract'],
					'price'                   => $product_option_value['price'],
					'price_prefix'            => $product_option_value['price_prefix'],
					'points'                  => $product_option_value['points'],
					'points_prefix'           => $product_option_value['points_prefix'],
					'weight'                  => $product_option_value['weight'],
					'weight_prefix'           => $product_option_value['weight_prefix']
				];
			}

			$product_option_data[] = [
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			];
		}

		return $product_option_data;
	}

	/**
	 * @param int $product_id
	 * @param int $product_option_value_id
	 *
	 * @return array
	 */
	public function getOptionValue(int $product_id, int $product_option_value_id): array {
		$query = $this->db->query("SELECT pov.`option_value_id`, ovd.`name`, pov.`quantity`, pov.`subtract`, pov.`price`, pov.`price_prefix`, pov.`points`, pov.`points_prefix`, pov.`weight`, pov.`weight_prefix` FROM `" . DB_PREFIX . "product_option_value` pov LEFT JOIN `" . DB_PREFIX . "option_value` ov ON (pov.`option_value_id` = ov.`option_value_id`) LEFT JOIN `" . DB_PREFIX . "option_value_description` ovd ON (ov.`option_value_id` = ovd.`option_value_id`) WHERE pov.`product_id` = '" . (int)$product_id . "' AND pov.`product_option_value_id` = '" . (int)$product_option_value_id . "' AND ovd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * @param int $option_id
	 *
	 * @return array
	 */
	public function getOptionValuesByOptionId(int $option_id): array {
		$query = $this->db->query("SELECT DISTINCT `option_value_id` FROM `" . DB_PREFIX . "product_option_value` WHERE `option_id` = '" . (int)$option_id . "'");

		return $query->rows;
	}

	/**
	 * @param int $product_id
	 *
	 * @return array
	 */
	public function getImages(int $product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_image` WHERE `product_id` = '" . (int)$product_id . "' ORDER BY `sort_order` ASC");

		return $query->rows;
	}

	/**
	 * @param int $product_id
	 *
	 * @return array
	 */
	public function getDiscounts(int $product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_discount` WHERE `product_id` = '" . (int)$product_id . "' ORDER BY `quantity`, `priority`, `price`");

		return $query->rows;
	}

	/**
	 * @param int $product_id
	 *
	 * @return array
	 */
	public function getSpecials(int $product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_special` WHERE `product_id` = '" . (int)$product_id . "' ORDER BY `priority`, `price`");

		return $query->rows;
	}

	/**
	 * @param int $product_id
	 *
	 * @return array
	 */
	public function getRewards(int $product_id): array {
		$product_reward_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_reward` WHERE `product_id` = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_reward_data[$result['customer_group_id']] = ['points' => $result['points']];
		}

		return $product_reward_data;
	}

	/**
	 * @param int $product_id
	 *
	 * @return array
	 */
	public function getDownloads(int $product_id): array {
		$product_download_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_to_download` WHERE `product_id` = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_download_data[] = $result['download_id'];
		}

		return $product_download_data;
	}

	/**
	 * @param int $product_id
	 *
	 * @return array
	 */
	public function getStores(int $product_id): array {
		$product_store_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_to_store` WHERE `product_id` = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_store_data[] = $result['store_id'];
		}

		return $product_store_data;
	}

	/**
	 * @param int $product_id
	 *
	 * @return array
	 */
	public function getSeoUrls(int $product_id): array {
		$product_seo_url_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `key` = 'product_id' AND `value` = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $product_seo_url_data;
	}

	/**
	 * @param int $product_id
	 *
	 * @return array
	 */
	public function getLayouts(int $product_id): array {
		$product_layout_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_to_layout` WHERE `product_id` = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $product_layout_data;
	}

	/**
	 * @param int $product_id
	 *
	 * @return array
	 */
	public function getRelated(int $product_id): array {
		$product_related_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_related` WHERE `product_id` = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}

		return $product_related_data;
	}

	/**
	 * @param int $product_id
	 *
	 * @return array
	 */
	public function getSubscriptions(int $product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_subscription` WHERE `product_id` = '" . (int)$product_id . "'");

		return $query->rows;
	}

	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function getTotalProducts(array $data = []): int {
		$sql = "SELECT COUNT(DISTINCT p.`product_id`) AS `total` FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (p.`product_id` = pd.`product_id`) WHERE pd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_master_id'])) {
			$sql .= " AND p.`master_id` = '" . (int)$data['filter_master_id'] . "'";
		}

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.`name` LIKE '" . $this->db->escape((string)$data['filter_name'] . '%') . "'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.`model` LIKE '" . $this->db->escape((string)$data['filter_model'] . '%') . "'";
		}

		if (isset($data['filter_price']) && $data['filter_price'] !== '') {
			$sql .= " AND p.`price` LIKE '" . $this->db->escape((string)$data['filter_price'] . '%') . "'";
		}

		if (isset($data['filter_quantity']) && $data['filter_quantity'] !== '') {
			$sql .= " AND p.`quantity` = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND p.`status` = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * @param int $tax_class_id
	 *
	 * @return int
	 */
	public function getTotalProductsByTaxClassId(int $tax_class_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "product` WHERE `tax_class_id` = '" . (int)$tax_class_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * @param int $stock_status_id
	 *
	 * @return int
	 */
	public function getTotalProductsByStockStatusId(int $stock_status_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "product` WHERE `stock_status_id` = '" . (int)$stock_status_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * @param int $weight_class_id
	 *
	 * @return int
	 */
	public function getTotalProductsByWeightClassId(int $weight_class_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "product` WHERE `weight_class_id` = '" . (int)$weight_class_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * @param int $length_class_id
	 *
	 * @return int
	 */
	public function getTotalProductsByLengthClassId(int $length_class_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "product` WHERE `length_class_id` = '" . (int)$length_class_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * @param int $download_id
	 *
	 * @return int
	 */
	public function getTotalProductsByDownloadId(int $download_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "product_to_download` WHERE `download_id` = '" . (int)$download_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * @param int $manufacturer_id
	 *
	 * @return int
	 */
	public function getTotalProductsByManufacturerId(int $manufacturer_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "product` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * @param int $attribute_id
	 *
	 * @return int
	 */
	public function getTotalProductsByAttributeId(int $attribute_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "product_attribute` WHERE `attribute_id` = '" . (int)$attribute_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * @param int $subscription_plan_id
	 *
	 * @return int
	 */
	public function getTotalProductsBySubscriptionPlanId(int $subscription_plan_id): int {
		$query = $this->db->query("SELECT COUNT(DISTINCT `product_id`) AS `total` FROM `" . DB_PREFIX . "product_subscription` WHERE `subscription_plan_id` = '" . (int)$subscription_plan_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * @param int $layout_id
	 *
	 * @return int
	 */
	public function getTotalProductsByLayoutId(int $layout_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "product_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * @param int $option_id
	 *
	 * @return int
	 */
	public function getTotalProductsByOptionId(int $option_id): int {
		$query = $this->db->query("SELECT COUNT(DISTINCT `product_id`) AS `total` FROM `" . DB_PREFIX . "product_option` WHERE `option_id` = '" . (int)$option_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * @param int $option_value_id
	 *
	 * @return int
	 */
	public function getTotalProductsByOptionValueId(int $option_value_id): int {
		$query = $this->db->query("SELECT COUNT(DISTINCT `product_id`) AS `total` FROM `" . DB_PREFIX . "product_option_value` WHERE `option_value_id` = '" . (int)$option_value_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * @param int $product_id
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array
	 */
	public function getReports(int $product_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT `ip`, `store_id`, `country`, `date_added` FROM `" . DB_PREFIX . "product_report` WHERE `product_id` = '" . (int)$product_id . "' ORDER BY `date_added` ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * @param int $product_id
	 *
	 * @return int
	 */
	public function getTotalReports(int $product_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "product_report` WHERE `product_id` = '" . (int)$product_id . "'");

		return (int)$query->row['total'];
	}
}
