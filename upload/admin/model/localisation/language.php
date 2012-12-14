<?php
class ModelLocalisationLanguage extends Model {
	public function addLanguage($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "language SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', locale = '" . $this->db->escape($data['locale']) . "', directory = '" . $this->db->escape($data['directory']) . "', filename = '" . $this->db->escape($data['filename']) . "', image = '" . $this->db->escape($data['image']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "'");
		
		$this->cache->delete('language');
		
		$language_id = $this->db->getLastId();

		// Attribute 
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $attribute) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($attribute['name']) . "'");
		}

		$this->cache->delete('attribute');

		// Attribute Group
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_group_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $attribute_group) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group_description SET attribute_group_id = '" . (int)$attribute_group['attribute_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($attribute_group['name']) . "'");
		}

		$this->cache->delete('attribute');
		
		// Banner
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $banner_image) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "banner_image_description SET banner_image_id = '" . (int)$banner_image['banner_image_id'] . "', banner_id = '" . (int)$banner_image['banner_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($banner_image['title']) . "'");
		}

		$this->cache->delete('attribute');
						
		// Category
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $category) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category['category_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($category['name']) . "', meta_description = '" . $this->db->escape($category['meta_description']) . "', meta_keyword = '" . $this->db->escape($category['meta_keyword']) . "', description = '" . $this->db->escape($category['description']) . "'");
		}

		$this->cache->delete('category');

		// Customer Group
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $customer_group) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group['customer_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($customer_group['name']) . "', description = '" . $this->db->escape($customer_group['description']) . "'");
		}
				
		// Download
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $download) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET download_id = '" . (int)$download['download_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($download['name']) . "'");
		}
		
		// Filter
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $filter) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter['filter_id'] . "', language_id = '" . (int)$language_id . "', filter_group_id = '" . (int)$filter['filter_group_id'] . "', name = '" . $this->db->escape($filter['name']) . "'");
		}
		
		// Filter Group
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_group_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $filter_group) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_description SET filter_group_id = '" . (int)$filter_group['filter_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($filter_group['name']) . "'");
		}
										
		// Information
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $information) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "information_description SET information_id = '" . (int)$information['information_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($information['title']) . "', description = '" . $this->db->escape($information['description']) . "'");
		}		

		$this->cache->delete('information');

		// Length
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $length) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "length_class_description SET length_class_id = '" . (int)$length['length_class_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($length['title']) . "', unit = '" . $this->db->escape($length['unit']) . "'");
		}	
		
		$this->cache->delete('length_class');

		// Option 
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $option) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "option_description SET option_id = '" . (int)$option['option_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($option['name']) . "'");
		}

		// Option Value
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $option_value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET option_value_id = '" . (int)$option_value['option_value_id'] . "', language_id = '" . (int)$language_id . "', option_id = '" . (int)$option_value['option_id'] . "', name = '" . $this->db->escape($option_value['name']) . "'");
		}
				
		// Order Status
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $order_status) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_status SET order_status_id = '" . (int)$order_status['order_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($order_status['name']) . "'");
		}	
		
		$this->cache->delete('order_status');
		
		// Product
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $product) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product['product_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($product['name']) . "', meta_description = '" . $this->db->escape($product['meta_description']) . "', meta_keyword = '" . $this->db->escape($product['meta_keyword']) . "', description = '" . $this->db->escape($product['description']) . "', tag = '" . $this->db->escape($product['tag']) . "'");
		}

		$this->cache->delete('product');
		
		// Product Attribute 
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $product_attribute) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_attribute['product_id'] . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" . $this->db->escape($product_attribute['text']) . "'");
		}
		
		// Return Action 
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_action WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $return_action) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "return_action SET return_action_id = '" . (int)$return_action['return_action_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($return_action['name']) . "'");
		}

		// Return Reason 
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_reason WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $return_reason) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "return_reason SET return_reason_id = '" . (int)$return_reason['return_reason_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($return_reason['name']) . "'");
		}
		
		// Return Status
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $return_status) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "return_status SET return_status_id = '" . (int)$return_status['return_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($return_status['name']) . "'");
		}
						
		// Stock Status
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $stock_status) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "stock_status SET stock_status_id = '" . (int)$stock_status['stock_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($stock_status['name']) . "'");
		}
		
		$this->cache->delete('stock_status');
		
		// Voucher Theme
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "voucher_theme_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $voucher_theme) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "voucher_theme_description SET voucher_theme_id = '" . (int)$voucher_theme['voucher_theme_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($voucher_theme['name']) . "'");
		}	
				
		// Weight Class
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $weight_class) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "weight_class_description SET weight_class_id = '" . (int)$weight_class['weight_class_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($weight_class['title']) . "', unit = '" . $this->db->escape($weight_class['unit']) . "'");
		}	
		
		$this->cache->delete('weight_class');
	}
	
	public function editLanguage($language_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "language SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', locale = '" . $this->db->escape($data['locale']) . "', directory = '" . $this->db->escape($data['directory']) . "', filename = '" . $this->db->escape($data['filename']) . "', image = '" . $this->db->escape($data['image']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "' WHERE language_id = '" . (int)$language_id . "'");
				
		$this->cache->delete('language');
	}
	
	public function deleteLanguage($language_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('language');
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "attribute_group_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "banner_image_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('category');
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_group_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "download_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "filter_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "filter_group_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "information_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('information');
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "length_class_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('length_class');
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "option_value_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('order_status');
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('product');
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "return_action WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('return_action');
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "return_reason WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('return_reason');
				
		$this->db->query("DELETE FROM " . DB_PREFIX . "return_status WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('return_status');
								
		$this->db->query("DELETE FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('stock_status');
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "voucher_theme_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('voucher_theme');
				
		$this->db->query("DELETE FROM " . DB_PREFIX . "weight_class_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('weight_class');
	}
	
	public function getLanguage($language_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");
	
		return $query->row;
	}

	public function getLanguages($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "language";
	
			$sort_data = array(
				'name',
				'code',
				'sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY sort_order, name";	
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
		} else {
			$language_data = $this->cache->get('language');
		
			if (!$language_data) {
				$language_data = array();
				
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language ORDER BY sort_order, name");
	
    			foreach ($query->rows as $result) {
      				$language_data[$result['code']] = array(
        				'language_id' => $result['language_id'],
        				'name'        => $result['name'],
        				'code'        => $result['code'],
						'locale'      => $result['locale'],
						'image'       => $result['image'],
						'directory'   => $result['directory'],
						'filename'    => $result['filename'],
						'sort_order'  => $result['sort_order'],
						'status'      => $result['status']
      				);
    			}	
			
				$this->cache->set('language', $language_data);
			}
		
			return $language_data;			
		}
	}

	public function getTotalLanguages() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "language");
		
		return $query->row['total'];
	}
}
?>