<?php
class ModelToolSeoUrl extends Model {
	public function generate() {
		 $this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "url_alias`");
		
		// Product
		$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product");
		
		foreach ($product_query->rows as $product) {
			if ($product['keyword']) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET `query` = 'route=product/product&product_id=" . (int)$product['product_id'] . "', `alias` = '" . $this->db->escape($product['keyword']) . "'");
			}
		}
		
		// Category
		$this->categories(0);
		
		// Manufacturer
		$manufacturer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer");

		foreach ($manufacturer_query->rows as $manufacturer) {
			if ($manufacturer['keyword']) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET `query` = 'route=product/manufacturer&manufacturer_id=" . (int)$manufacturer['manufacturer_id'] . "', `alias` = '" . $this->db->escape($manufacturer['keyword']) . "'");
			}
			
			$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE manufacturer_id = '" . (int)$manufacturer['manufacturer_id'] . "'");
		
			foreach ($product_query->rows as $product) {
				if (($manufacturer['keyword']) && ($product['keyword'])) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET `query` = 'route=product/product&manufacturer_id=" . (int)$manufacturer['manufacturer_id'] . "&product_id=" . (int)$product['product_id'] . "', `alias` = '" . $this->db->escape($manufacturer['keyword'] . '/' . $product['keyword']) . "'");
				}
			}			
		}

		// Information
		$information_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information");
		
		foreach ($information_query->rows as $information) {
			if ($information['keyword']) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET `query` = 'route=information/information&information_id=" . (int)$information['information_id'] . "', `alias` = '" . $this->db->escape($information['keyword']) . "'");
			}
		}
	}
	
	private function categories($path) {
		$keyword = '';
		
		$parts = explode('_', $path);		

		foreach ($parts as $category_id) {
			$category_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE category_id = '" . (int)$category_id . "'");
			
			if ($category_query->num_rows) {
				$keyword .= $category_query->row['keyword'] . '/';
			}
		}

		$category_id = array_pop($parts);

		$category_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$category_id . "'");
		
		foreach ($category_query->rows as $category) {
			if (!$path) {
				$new_path = $category['category_id'];
			} else {
				$new_path = $path . '_' . $category['category_id'];
			}
			
			if ($category['keyword']) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET `query` = 'route=product/category&path=" . $this->db->escape($new_path) . "', `alias` = '" . $this->db->escape($keyword . $category['keyword']) . "'");
			}	
			
			$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE p2c.category_id = '" . (int)$category['category_id'] . "'");

			foreach ($product_query->rows as $product) {
				if (($category['keyword']) && ($product['keyword'])) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET `query` = 'route=product/product&path=" . $this->db->escape($new_path) . "&product_id=" . (int)$product['product_id'] . "', `alias` = '" . $this->db->escape($keyword . $category['keyword'] . '/' . $product['keyword']) . "'");
				}
			}	

			$this->categories($new_path);
		}
	}
}
?>