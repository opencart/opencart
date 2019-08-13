<?php
class ModelCatalogProduct extends Model {
	public function addProduct($data) {
		// If product is variant we need to get values from the master product
		if ($data['master_id']) {
			$master_info = $this->model_catalog_product->getProduct($data['master_id']);

			if ($master_info) {
				if (isset($data['override'])) {
					$override = $data['override'];
				} else {
					$override = array();
				}

				$ignore = array(
					'product_id',
					'master_id',
					'quantity',
					'variant',
					'override'
				);

				foreach ($master_info as $key => $value) {
					// So if key not in override we replace with master value
					// If key not in ignore list then replace with master value
					if (!array_key_exists($key, $override) && !in_array($key, $ignore)) {
						$data[$key] = $value;
					}
				}

				// Descriptions
				$product_descriptions = $this->model_catalog_product->getProductDescriptions($data['master_id']);

				foreach ($product_descriptions as $language_id => $product_description) {
					foreach ($product_description as $key => $value) {
						if (!isset($override['product_description'][$language_id][$key])) {
							$data['product_description'][$language_id][$key] = $value;
						}
					}
				}

				// Attributes
				if (!isset($override['product_attribute'])) {
					$data['product_attribute'] = $this->model_catalog_product->getProductAttributes($data['master_id']);
				}

				// Category
				if (!isset($override['product_category'])) {
					$data['product_category'] = $this->model_catalog_product->getProductCategories($data['master_id']);
				}

				// Discounts
				if (!isset($override['product_discount'])) {
					$data['product_discount'] = $this->model_catalog_product->getProductDiscounts($data['master_id']);
				}

				// Downloads
				if (!isset($override['product_download'])) {
					$data['product_download'] = $this->model_catalog_product->getProductDownloads($data['master_id']);
				}

				// Filters
				if (!isset($override['product_filter'])) {
					$data['product_filter'] = $this->model_catalog_product->getProductFilters($data['master_id']);
				}

				// Filters
				if (!isset($override['product_image'])) {
					$data['product_image'] = $this->model_catalog_product->getProductImages($data['master_id']);
				}

				// Layouts
				if (!isset($override['product_layout'])) {
					$data['product_layout'] = $this->model_catalog_product->getProductLayouts($data['master_id']);
				}

				// Recurrings
				if (!isset($override['product_recurring'])) {
					$data['product_recurring'] = $this->model_catalog_product->getProductRecurrings($data['master_id']);
				}

				// Related
				if (!isset($override['product_related'])) {
					$data['product_related'] = $this->model_catalog_product->getProductRelated($data['master_id']);
				}

				// Rewards
				if (!isset($override['product_reward'])) {
					$data['product_reward'] = $this->model_catalog_product->getProductRewards($data['master_id']);
				}

				// Specials
				if (!isset($override['product_special'])) {
					$data['product_special'] = $this->model_catalog_product->getProductSpecials($data['master_id']);
				}

				// Stores
				if (!isset($override['product_store'])) {
					$data['product_store'] = $this->model_catalog_product->getProductStores($data['master_id']);
				}
			}
		}

		$this->db->query("INSERT INTO " . DB_PREFIX . "product SET master_id = '" . (int)$data['master_id'] . "', model = '" . $this->db->escape((string)$data['model']) . "', sku = '" . $this->db->escape((string)$data['sku']) . "', upc = '" . $this->db->escape((string)$data['upc']) . "', ean = '" . $this->db->escape((string)$data['ean']) . "', jan = '" . $this->db->escape((string)$data['jan']) . "', isbn = '" . $this->db->escape((string)$data['isbn']) . "', mpn = '" . $this->db->escape((string)$data['mpn']) . "', location = '" . $this->db->escape((string)$data['location']) . "', variant = '" . $this->db->escape(!empty($data['variant']) ? json_encode($data['variant']) : '') . "', override = '" . $this->db->escape(!empty($data['override']) ? json_encode($data['override']) : '') . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape((string)$data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW(), date_modified = NOW()");

		$product_id = $this->db->getLastId();

		if ($data['image']) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape((string)$data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}

		// Description
		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// Categories
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		// Filters
		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		// Stores
		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		// Downloads
		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		// Related
		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		// Attributes
		if (isset($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					// Removes duplicates
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "' AND language_id = '" . (int)$language_id . "'");

						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
					}
				}
			}
		}

		// Options
		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					if (isset($product_option['product_option_value'])) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

						$product_option_id = $this->db->getLastId();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		// Recurring
		if (isset($data['product_recurring'])) {
			foreach ($data['product_recurring'] as $product_recurring) {
				if (isset($product_recurring['recurring_id'])) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_recurring['customer_group_id'] . "', `recurring_id` = '" . (int)$product_recurring['recurring_id'] . "'");
				}
			}
		}

		// Discounts
		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		// Specials
		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		// Images
		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}

		// Reward
		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $product_reward) {
				if ((int)$product_reward['points'] > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$product_reward['points'] . "'");
				}
			}
		}
		
		// SEO URL
		if (isset($data['product_seo_url'])) {
			foreach ($data['product_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if ($keyword) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($keyword) . "', push = '" . $this->db->escape('route=product/product&product_id=' . (int)$product_id) . "'");
					}
				}
			}
		}

		// Layout
		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->cache->delete('product');

		return $product_id;
	}

	public function editProduct($product_id, $data) {

		echo 'CALL editProduct' . "\n";

		//print_r($this->request->get);
		//print_r($this->request->post);

		// If product is variant we need to get values from the master product
		if ($data['master_id']) {


			echo 'master_id=' . $data['master_id'] . "\n";
			echo 'product_id=' . $product_id . "\n";

			echo "\n";
			print_r($data['override']);
			echo "\n";
			exit;






			//print_r($data);


			$master_info = $this->model_catalog_product->getProduct($data['master_id']);

			if ($master_info) {




				if (isset($data['override'])) {
					$override = (array)$data['override'];
				} else {
					$override = array();
				}

				$ignore = array(
					'product_id',
					'master_id',
					'quantity',
					'variant',
					'override'
				);

				foreach ($master_info as $key => $value) {
					// So if key not in override we replace with master value
					// If key not in ignore list then replace with master value
					if (!array_key_exists($key, $override) && !in_array($key, $ignore)) {
						$data[$key] = $value;
					}
				}




				// Descriptions
				$product_descriptions = $this->getProductDescriptions($data['master_id']);

				foreach ($product_descriptions as $language_id => $product_description) {
					foreach ($product_description as $key => $value) {
						if (!isset($override['product_description'][$language_id][$key])) {
							$data['product_description'][$language_id][$key] = $value;
						}
					}
				}

				// Attributes
				if (!isset($override['product_attribute'])) {
					$data['product_attribute'] = $this->model_catalog_product->getProductAttributes($data['master_id']);
				}

				// Category
				if (!isset($override['product_category'])) {
					$data['product_category'] = $this->model_catalog_product->getProductCategories($data['master_id']);
				}

				// Discounts
				if (!isset($override['product_discount'])) {
					$data['product_discount'] = $this->model_catalog_product->getProductDiscounts($data['master_id']);
				}

				// Downloads
				if (!isset($override['product_download'])) {
					$data['product_download'] = $this->model_catalog_product->getProductDownloads($data['master_id']);
				}

				// Filters
				if (!isset($override['product_filter'])) {
					$data['product_filter'] = $this->model_catalog_product->getProductFilters($data['master_id']);
				}

				// Filters
				if (!isset($override['product_image'])) {
					$data['product_image'] = $this->model_catalog_product->getProductImages($data['master_id']);
				}

				// Layouts
				if (!isset($override['product_layout'])) {
					$data['product_layout'] = $this->model_catalog_product->getProductLayouts($data['master_id']);
				}

				// Recurrings
				if (!isset($override['product_recurring'])) {
					$data['product_recurring'] = $this->model_catalog_product->getProductRecurrings($data['master_id']);
				}

				// Related
				if (!isset($override['product_related'])) {
					$data['product_related'] = $this->model_catalog_product->getProductRelated($data['master_id']);
				}

				// Rewards
				if (!isset($override['product_reward'])) {
					$data['product_reward'] = $this->model_catalog_product->getProductRewards($data['master_id']);
				}

				// Specials
				if (!isset($override['product_special'])) {
					$data['product_special'] = $this->model_catalog_product->getProductSpecials($data['master_id']);
				}

				// Stores
				if (!isset($override['product_store'])) {
					$data['product_store'] = $this->model_catalog_product->getProductStores($data['master_id']);
				}
			}
		}







		$this->db->query("UPDATE " . DB_PREFIX . "product SET model = '" . $this->db->escape((string)$data['model']) . "', sku = '" . $this->db->escape((string)$data['sku']) . "', upc = '" . $this->db->escape((string)$data['upc']) . "', ean = '" . $this->db->escape((string)$data['ean']) . "', jan = '" . $this->db->escape((string)$data['jan']) . "', isbn = '" . $this->db->escape((string)$data['isbn']) . "', mpn = '" . $this->db->escape((string)$data['mpn']) . "', location = '" . $this->db->escape((string)$data['location']) . "', variant = '" . $this->db->escape(!empty($data['variant']) ? json_encode($data['variant']) : '') . "', override = '" . $this->db->escape(!empty($data['override']) ? json_encode($data['override']) : '') . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape((string)$data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . (int)$data['tax_class_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");

		if ($data['image']) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape((string)$data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}

		// Description
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// Categories
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		// Filters
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_filter'])) {
			foreach ($data['product_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_filter SET product_id = '" . (int)$product_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		// Stores
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_store'])) {
			foreach ($data['product_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		// Downloads
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		// Related
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		// Attributes
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");

		if (!empty($data['product_attribute'])) {
			foreach ($data['product_attribute'] as $product_attribute) {
				if ($product_attribute['attribute_id']) {
					// Removes duplicates
					$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

					foreach ($product_attribute['product_attribute_description'] as $language_id => $product_attribute_description) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_id . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($product_attribute_description['text']) . "'");
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
						$this->db->query("INSERT INTO `" . DB_PREFIX . "product_option` SET `product_option_id` = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "'");

						$product_option_id = $this->db->getLastId();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO `" . DB_PREFIX . "product_option_value` SET `product_option_value_id` = '" . (int)$product_option_value['product_option_value_id'] . "', product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						}
					}
				} else {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "product_option` SET `product_option_id` = '" . (int)$product_option['product_option_id'] . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', value = '" . $this->db->escape($product_option['value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}

		// Recurring
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_recurring` WHERE `product_id` = " . (int)$product_id);

		if (isset($data['product_recurring'])) {
			foreach ($data['product_recurring'] as $product_recurring) {
				if (isset($product_recurring['recurring_id'])) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "product_recurring` SET `product_id` = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_recurring['customer_group_id'] . "', `recurring_id` = '" . (int)$product_recurring['recurring_id'] . "'");
				}
			}
		}

		// Discounts
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "'");
			}
		}

		// Specials
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', date_end = '" . $this->db->escape($product_special['date_end']) . "'");
			}
		}

		// Images
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $product_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape($product_image['image']) . "', sort_order = '" . (int)$product_image['sort_order'] . "'");
			}
		}

		// Rewards
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_reward'])) {
			foreach ($data['product_reward'] as $customer_group_id => $value) {
				if ((int)$value['points'] > 0) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_reward SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$customer_group_id . "', points = '" . (int)$value['points'] . "'");
				}
			}
		}
		
		// SEO URL
		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'product_id=" . (int)$product_id . "'");
		
		if (isset($data['product_seo_url'])) {
			foreach ($data['product_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if ($keyword) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($keyword) . "', push = '" . $this->db->escape('route=product/product&product_id=' . (int)$product_id) . "'");
					}
				}
			}
		}

		// Layout
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['product_layout'])) {
			foreach ($data['product_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_layout SET product_id = '" . (int)$product_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}







		// We need to update variant products with the new master product data.
		$products = $this->model_catalog_product->getProducts(array('filter_master_id' => $product_id));

		foreach ($products as $product) {
			$variant_data = $data;

			$override = (array)json_decode($product['override'], true);

			$replace = array(
				'master_id',
				'quantity',
				'override',
				'variant',
				'product_seo_url'
			);

			foreach ($product as $key => $value) {
				// We want to check override if to use override value
				if (array_key_exists($key, $override) || in_array($key, $replace)) {
					// Restore default value
					$variant_data[$key] = $value;
				}
			}

			// Make sure these are arrays not strings
			$variant_data['variant'] = (array)json_decode($product['variant'], true);

			// Descriptions
			$product_descriptions = $this->model_catalog_product->getProductDescriptions($product['product_id']);

			foreach ($product_descriptions as $language_id => $product_description) {
				foreach ($product_description as $key => $value) {
					if (isset($override['product_description'][$language_id][$key])) {
						$variant_data['product_description'][$language_id][$key] = $value;
					}
				}
			}

			// Category
			if (isset($override['product_category'])) {
				$variant_data['product_category'] = $this->model_catalog_product->getProductCategories($product['product_id']);
			}

			// Filter
			if (isset($override['product_filter'])) {
				$variant_data['product_filter'] = $this->model_catalog_product->getProductFilters($product['product_id']);
			}

			// Stores
			if (isset($override['product_store'])) {
				$variant_data['product_store'] = $this->model_catalog_product->getProductStores($product['product_id']);
			}

			// Downloads
			if (isset($override['product_download'])) {
				$variant_data['product_download'] = $this->model_catalog_product->getProductDownloads($product['product_id']);
			}

			// Related
			if (isset($override['product_related'])) {
				$variant_data['product_related'] = $this->model_catalog_product->getProductRelated($product['product_id']);
			}

			// Attributes
			if (isset($override['product_attribute'])) {
				$variant_data['product_attribute'] = $this->model_catalog_product->getProductAttributes($product['product_id']);
			}

			// Recurring
			if (isset($override['product_recurring'])) {
				$variant_data['product_recurring'] = $this->model_catalog_product->getProductRecurrings($product['product_id']);
			}

			// Discount
			if (isset($override['product_discount'])) {
				$variant_data['product_discount'] = $this->model_catalog_product->getProductDiscounts($product['product_id']);
			}

			// Special
			if (isset($override['product_special'])) {
				$variant_data['product_special'] = $this->model_catalog_product->getProductSpecials($product['product_id']);
			}

			// Images
			if (isset($override['product_image'])) {
				$variant_data['product_image'] = $this->model_catalog_product->getProductImages($product['product_id']);
			}

			// Reward
			if (isset($override['product_reward'])) {
				$variant_data['product_reward'] = $this->model_catalog_product->getProductRewards($product['product_id']);
			}

			// SEO URL
			$variant_data['product_seo_url'] = $this->model_catalog_product->getProductSeoUrls($product['product_id']);

			// Layout
			if (isset($override['product_layout'])) {
				$variant_data['product_layout'] = $this->model_catalog_product->getProductLayouts($product['product_id']);
			}

			//$this->model_catalog_product->editProduct($product['product_id'], $variant_data);
		}








		$this->cache->delete('product');
	}

	public function copyProduct($product_id) {
		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			$data = $product_info;

			$data['sku'] = '';
			$data['upc'] = '';
			$data['status'] = '0';

			$data['product_attribute'] = $this->model_catalog_product->getProductAttributes($product_id);
			$data['product_category'] = $this->model_catalog_product->getProductCategories($product_id);
			$data['product_description'] = $this->model_catalog_product->getProductDescriptions($product_id);
			$data['product_discount'] = $this->model_catalog_product->getProductDiscounts($product_id);
			$data['product_download'] = $this->model_catalog_product->getProductDownloads($product_id);
			$data['product_filter'] = $this->model_catalog_product->getProductFilters($product_id);
			$data['product_image'] = $this->model_catalog_product->getProductImages($product_id);
			$data['product_layout'] = $this->model_catalog_product->getProductLayouts($product_id);
			$data['product_option'] = $this->model_catalog_product->getProductOptions($product_id);
			$data['product_recurring'] = $this->model_catalog_product->getProductRecurrings($product_id);
			$data['product_related'] = $this->model_catalog_product->getProductRelated($product_id);
			$data['product_reward'] = $this->model_catalog_product->getProductRewards($product_id);
			$data['product_special'] = $this->model_catalog_product->getProductSpecials($product_id);
			$data['product_store'] = $this->model_catalog_product->getProductStores($product_id);

			$this->model_catalog_product->addProduct($data);
		}
	}

	public function deleteProduct($product_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_recurring WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'product_id=" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_product WHERE product_id = '" . (int)$product_id . "'");

		$this->db->query("UPDATE " . DB_PREFIX . "product SET master_id = '0' WHERE master_id = '" . (int)$product_id . "'");

		$this->cache->delete('product');
	}

	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getProducts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_master_id'])) {
			$sql .= " AND p.master_id = '" . (int)$data['filter_master_id'] . "'";
		}

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape((string)$data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape((string)$data['filter_model']) . "%'";
		}

		if (!empty($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape((string)$data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && $data['filter_quantity'] !== '') {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
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

	public function getProductsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getProductDescriptions($product_id) {
		$product_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			);
		}

		return $product_description_data;
	}

	public function getProductCategories($product_id) {
		$product_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}

	public function getProductFilters($product_id) {
		$product_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_filter_data[] = $result['filter_id'];
		}

		return $product_filter_data;
	}

	public function getProductAttributes($product_id) {
		$product_attribute_data = array();

		$product_attribute_query = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' GROUP BY attribute_id");

		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = array();

			$product_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");

			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
				$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
			}

			$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'product_attribute_description' => $product_attribute_description_data
			);
		}

		return $product_attribute_data;
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($product_option_query->rows as $product_option) {
			$product_option_value_data = array();

			$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON(pov.option_value_id = ov.option_value_id) WHERE pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' ORDER BY ov.sort_order ASC");

			foreach ($product_option_value_query->rows as $product_option_value) {
				$product_option_value_data[] = array(
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
				);
			}

			$product_option_data[] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => $product_option['value'],
				'required'             => $product_option['required']
			);
		}

		return $product_option_data;
	}

	public function getProductOptionValue($product_id, $product_option_value_id) {
		$query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_id = '" . (int)$product_id . "' AND pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getProductOptionValues($product_option_id) {
		$query = $this->db->query("SELECT product_option_value_id, option_value_id, quantity, subtract, price, price_prefix, points, points_prefix, weight, weight_prefix FROM " . DB_PREFIX . "product_option_value WHERE product_option_id = '" . (int)$product_option_id . "'");

		return $query->rows;
	}

	public function getProductOptionValuesByOptionId($option_id) {
		$query = $this->db->query("SELECT DISTINCT option_value_id FROM " . DB_PREFIX . "product_option_value WHERE option_id = '" . (int)$option_id . "'");

		return $query->rows;
	}

	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' ORDER BY quantity, priority, price");

		return $query->rows;
	}

	public function getProductSpecials($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' ORDER BY priority, price");

		return $query->rows;
	}

	public function getProductRewards($product_id) {
		$product_reward_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}

		return $product_reward_data;
	}

	public function getProductDownloads($product_id) {
		$product_download_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_download_data[] = $result['download_id'];
		}

		return $product_download_data;
	}

	public function getProductStores($product_id) {
		$product_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_store_data[] = $result['store_id'];
		}

		return $product_store_data;
	}
	
	public function getProductSeoUrls($product_id) {
		$product_seo_url_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'product_id=" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $product_seo_url_data;
	}
	
	public function getProductLayouts($product_id) {
		$product_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $product_layout_data;
	}

	public function getProductRelated($product_id) {
		$product_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}

		return $product_related_data;
	}

	public function getProductRecurrings($product_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_recurring` WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;
	}

	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_master_id'])) {
			$sql .= " AND p.master_id = '" . (int)$data['filter_master_id'] . "'";
		}

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape((string)$data['filter_name']) . "%'";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape((string)$data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape((string)$data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && $data['filter_quantity'] !== '') {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalProductsByTaxClassId($tax_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByStockStatusId($stock_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByWeightClassId($weight_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByLengthClassId($length_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE length_class_id = '" . (int)$length_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_download WHERE download_id = '" . (int)$download_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByProfileId($recurring_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_recurring WHERE recurring_id = '" . (int)$recurring_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(DISTINCT product_id) AS total FROM " . DB_PREFIX . "product_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByOptionValueId($option_value_id) {
		$query = $this->db->query("SELECT COUNT(DISTINCT product_id) AS total FROM " . DB_PREFIX . "product_option_value WHERE option_value_id = '" . (int)$option_value_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductOptionsByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}
}