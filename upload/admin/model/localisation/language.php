<?php
class ModelLocalisationLanguage extends Model {
	public function addLanguage($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "language SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', locale = '" . $this->db->escape($data['locale']) . "', directory = '" . $this->db->escape($data['directory']) . "', filename = '" . $this->db->escape($data['filename']) . "', image = '" . $this->db->escape($data['image']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "'");
		
		$this->cache->delete('language');
		
		$language_id = $this->db->getLastId();

		// Category
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $category) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category['category_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($category['name']) . "', meta_description= '" . $this->db->escape($category['meta_description']) . "', description = '" . $this->db->escape($category['description']) . "'");
		}

		$this->cache->delete('category');

		// Coupon
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $coupon) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_description SET coupon_id = '" . (int)$coupon['coupon_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($coupon['name']) . "', description = '" . $this->db->escape($coupon['description']) . "'");
		}
		
		// Download
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $download) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET download_id = '" . (int)$download['download_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($download['name']) . "'");
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
		
		// Order Status
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $order_status) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_status SET order_status_id = '" . (int)$order_status['order_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($order_status['name']) . "'");
		}	
		
		$this->cache->delete('order_status');
		
		// Product
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $product) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product['product_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($product['name']) . "', meta_description= '" . $this->db->escape($product['meta_description']) . "', description = '" . $this->db->escape($product['description']) . "'");
		}

		$this->cache->delete('product');

		// Product Option
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $product_option) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_description SET product_option_id = '" . (int)$product_option['product_option_id'] . "', language_id = '" . (int)$language_id . "', product_id = '" . (int)$product_option['product_id'] . "', name = '" . $this->db->escape($product_option['name']) . "'");
		}
		
		// Product Option Value
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $product_option_value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value_description SET product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "', language_id = '" . (int)$language_id . "', product_id = '" . (int)$product_option_value['product_id'] . "', name = '" . $this->db->escape($product_option_value['name']) . "'");
		}

		// Stock Status
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $stock_status) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "stock_status SET stock_status_id = '" . (int)$stock_status['stock_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($stock_status['name']) . "'");
		}
		
		$this->cache->delete('stock_status');
		
		// Store
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($query->rows as $store) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "store_description SET store_id = '" . (int)$store['store_id'] . "', language_id = '" . (int)$language_id . "', description = '" . $this->db->escape($store['description']) . "'");
		}
		
		$this->cache->delete('store');		
		
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
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "store_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('store');
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('category');
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "coupon_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "download_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "information_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('information');
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "length_class_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('length_class');
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_status WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('order_status');
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_description WHERE language_id = '" . (int)$language_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value_description WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('product');
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "stock_status WHERE language_id = '" . (int)$language_id . "'");
		
		$this->cache->delete('stock_status');
		
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