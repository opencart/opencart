<?php
class ModelSettingStore extends Model {
	public function addStore($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "store SET name = '" . $this->db->escape($data['name']) . "', url = '" . $this->db->escape($data['url']) . "', title = '" . $this->db->escape($data['title']) . "', meta_description = '" . $this->db->escape($data['meta_description']) . "', template = '" . $this->db->escape($data['template']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', language = '" . $this->db->escape($data['language']) . "', currency = '" . $this->db->escape($data['currency']) . "', tax = '" . (int)$data['tax'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', customer_price = '" . (int)$data['customer_price'] . "', customer_approval = '" . (int)$data['customer_approval'] . "', guest_checkout = '" . (int)$data['guest_checkout'] . "', account_id = '" . (int)$data['account_id'] . "', checkout_id = '" . (int)$data['checkout_id'] . "', stock_display = '" . (int)$data['stock_display'] . "', stock_checkout = '" . (int)$data['stock_checkout'] . "', order_status_id = '" . (int)$data['order_status_id'] . "', logo = '" . $this->db->escape($data['logo']) . "',  icon = '" . $this->db->escape($data['icon']) . "', image_thumb_width = '" . (int)$data['image_thumb_width'] . "', image_thumb_height = '" . (int)$data['image_thumb_height'] . "', image_popup_width = '" . (int)$data['image_popup_width'] . "', image_popup_height = '" . (int)$data['image_popup_height'] . "', image_category_width = '" . (int)$data['image_category_width'] . "', image_category_height = '" . (int)$data['image_category_height'] . "', image_product_width = '" . (int)$data['image_product_width'] . "', image_product_height = '" . (int)$data['image_product_height'] . "', image_additional_width = '" . (int)$data['image_additional_width'] . "', image_additional_height = '" . (int)$data['image_additional_height'] . "', image_related_width = '" . (int)$data['image_related_width'] . "', image_related_height = '" . (int)$data['image_related_height'] . "', image_cart_width = '" . (int)$data['image_cart_width'] . "', image_cart_height = '" . (int)$data['image_cart_height'] . "', `ssl` = '" . (int)$data['ssl'] . "'");
		
		$store_id = $this->db->getLastId();
		
		foreach ($data['store_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "store_description SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		$this->cache->delete('store');
		
		return $store_id;
	}
	
	public function editStore($store_id, $data) {
      	$this->db->query("UPDATE " . DB_PREFIX . "store SET name = '" . $this->db->escape($data['name']) . "', url = '" . $this->db->escape($data['url']) . "', title = '" . $this->db->escape($data['title']) . "', meta_description = '" . $this->db->escape($data['meta_description']) . "', template = '" . $this->db->escape($data['template']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', language = '" . $this->db->escape($data['language']) . "', currency = '" . $this->db->escape($data['currency']) . "', tax = '" . (int)$data['tax'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', customer_price = '" . (int)$data['customer_price'] . "', customer_approval = '" . (int)$data['customer_approval'] . "', guest_checkout = '" . (int)$data['guest_checkout'] . "', account_id = '" . (int)$data['account_id'] . "', checkout_id = '" . (int)$data['checkout_id'] . "', stock_display = '" . (int)$data['stock_display'] . "', stock_checkout = '" . (int)$data['stock_checkout'] . "', order_status_id = '" . (int)$data['order_status_id'] . "', logo = '" . $this->db->escape($data['logo']) . "',  icon = '" . $this->db->escape($data['icon']) . "', image_thumb_width = '" . (int)$data['image_thumb_width'] . "', image_thumb_height = '" . (int)$data['image_thumb_height'] . "', image_popup_width = '" . (int)$data['image_popup_width'] . "', image_popup_height = '" . (int)$data['image_popup_height'] . "', image_category_width = '" . (int)$data['image_category_width'] . "', image_category_height = '" . (int)$data['image_category_height'] . "', image_product_width = '" . (int)$data['image_product_width'] . "', image_product_height = '" . (int)$data['image_product_height'] . "', image_additional_width = '" . (int)$data['image_additional_width'] . "', image_additional_height = '" . (int)$data['image_additional_height'] . "', image_related_width = '" . (int)$data['image_related_width'] . "', image_related_height = '" . (int)$data['image_related_height'] . "', image_cart_width = '" . (int)$data['image_cart_width'] . "', image_cart_height = '" . (int)$data['image_cart_height'] . "', `ssl` = '" . (int)$data['ssl'] . "', catalog_limit = '" . (int)$data['catalog_limit'] . "', cart_weight = '" . (int)$data['cart_weight'] . "' WHERE store_id = '" . (int)$store_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "store_description WHERE store_id = '" . (int)$store_id . "'");
		
		foreach ($data['store_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "store_description SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		
		$this->cache->delete('store');
	}
	
	public function deleteStore($store_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "store WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "store_description WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_to_store WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "information_to_store WHERE store_id = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "manufacturer_to_store WHERE store_id = '" . (int)$store_id . "'");
	
		$this->cache->delete('store');
	}	
	
	public function getStore($store_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "store WHERE store_id = '" . (int)$store_id . "'");
		
		return $query->row;
	}
	
	public function getStoreDescriptions($store_id) {
		$store_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store_description WHERE store_id = '" . (int)$store_id . "'");
		
		foreach ($query->rows as $result) {
			$store_description_data[$result['language_id']] = array('description' => $result['description']);
		}
		
		return $store_description_data;
	}
	
	public function getStores($data = array()) {
		$store_data = $this->cache->get('store');
	
		if (!$store_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY url");

			$store_data = $query->rows;
		
			$this->cache->set('store', $store_data);
		}
	 
		return $store_data;
	}

	public function getTotalStores() {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store");
		
		return $query->row['total'];
	}	

	public function getTotalStoresByLanguage($language) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store WHERE language = '" . $this->db->escape($language) . "'");
		
		return $query->row['total'];		
	}
	
	public function getTotalStoresByCurrency($currency) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store WHERE currency = '" . $this->db->escape($currency) . "'");
		
		return $query->row['total'];		
	}
	
	public function getTotalStoresByCountryId($country_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store WHERE country_id = '" . (int)$country_id . "'");
		
		return $query->row['total'];		
	}
	
	public function getTotalStoresByZoneId($zone_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store WHERE zone_id = '" . (int)$zone_id . "'");
		
		return $query->row['total'];		
	}
	
	public function getTotalStoresByCustomerGroupId($customer_group_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store WHERE customer_group_id = '" . (int)$customer_group_id . "'");
		
		return $query->row['total'];		
	}	
	
	public function getTotalStoresByInformationId($information_id) {
      	$account_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store WHERE account_id = '" . (int)$information_id . "'");
      	
		$checkout_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store WHERE checkout_id = '" . (int)$information_id . "'");
		
		return ($account_query->row['total'] + $checkout_query->row['total']);
	}
	
	public function getTotalStoresByOrderStatusId($order_status_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "store WHERE order_status_id = '" . (int)$order_status_id . "'");
		
		return $query->row['total'];		
	}	
}
?>