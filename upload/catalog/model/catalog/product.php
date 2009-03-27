<?php
class ModelCatalogProduct extends Model {
	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, ss.name AS stock FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) LEFT JOIN manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN stock_status ss ON (p.stock_status_id = ss.stock_status_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->language->getId() . "' AND ss.language_id = '" . (int)$this->language->getId() . "' AND p.date_available <= NOW() AND p.status = '1'");
	
		return $query->row;
	}

	public function getProductsByCategoryId($category_id, $sort = 'pd.name', $order = 'ASC', $start = 0, $limit = 20) {
		$sql = "SELECT *, pd.name AS name, p.image, m.name AS manufacturer, ss.name AS stock, (SELECT AVG(r.rating) FROM review r WHERE p.product_id = r.product_id GROUP BY r.product_id) AS rating FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) LEFT JOIN manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN stock_status ss ON (p.stock_status_id = ss.stock_status_id) LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND pd.language_id = '" . (int)$this->language->getId() . "' AND ss.language_id = '" . (int)$this->language->getId() . "' AND p2c.category_id = '" . (int)$category_id. "'";

		$sort_data = array(
			'pd.name',
			'p.price',
			'rating'
		);	
			
		if (in_array($sort, $sort_data)) {
			$sql .= " ORDER BY " . $sort;
		} else {
			$sql .= " ORDER BY pd.name";	
		}
			
		if ($order == 'DESC') {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
			
		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
		
		$query = $this->db->query($sql);
								  
		return $query->rows;
	} 
	
	public function getTotalProductsByCategoryId($category_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM product_to_category p2c LEFT JOIN product p ON (p2c.product_id = p.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND p2c.category_id = '" . (int)$category_id . "'");
		
		return $query->row['total'];
	}

	public function getProductsByManufacturerId($manufacturer_id, $sort = 'pd.name', $order = 'ASC', $start = 0, $limit = 20) {
		$sql = "SELECT *, pd.name AS name, p.image, m.name AS manufacturer, ss.name AS stock, (SELECT AVG(r.rating) FROM  review r WHERE p.product_id = r.product_id GROUP BY r.product_id) AS rating FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) LEFT JOIN manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN stock_status ss ON (p.stock_status_id = ss.stock_status_id) WHERE p.status = '1' AND p.date_available <= NOW() AND pd.language_id = '" . (int)$this->language->getId() . "' AND ss.language_id = '" . (int)$this->language->getId() . "' AND m.manufacturer_id = '" . (int)$manufacturer_id. "'";

		$sort_data = array(
			'pd.name',
			'p.price',
			'rating'
		);	
			
		if (in_array($sort, $sort_data)) {
			$sql .= " ORDER BY " . $sort;
		} else {
			$sql .= " ORDER BY pd.name";	
		}
			
		if ($order == 'DESC') {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
			
		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	} 

	public function getTotalProductsByManufacturerId($manufacturer_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM product WHERE status = '1' AND date_available <= NOW() AND manufacturer_id = '" . (int)$manufacturer_id . "'");
		
		return $query->row['total'];
	}
	
	public function getProductsByKeyword($keyword, $description = FALSE, $sort = 'pd.name', $order = 'ASC', $start = 0, $limit = 20) {
		$sql = "SELECT *, pd.name AS name, p.image, m.name AS manufacturer, ss.name AS stock, (SELECT AVG(r.rating) FROM  review r WHERE p.product_id = r.product_id GROUP BY r.product_id) AS rating FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) LEFT JOIN manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN stock_status ss ON (p.stock_status_id = ss.stock_status_id) WHERE pd.language_id = '" . (int)$this->language->getId() . "' AND ss.language_id = '" . (int)$this->language->getId() . "'";
		
		if (!$description) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($keyword) . "%'";
		} else {
			$sql .= " AND (pd.name LIKE '%" . $this->db->escape($keyword) . "%' OR pd.description LIKE '%" . $this->db->escape($keyword) . "%')";
		}
		
		$sql .= " AND p.status = '1' AND p.date_available <= NOW()";
		
		$sort_data = array(
			'pd.name',
			'p.price',
			'rating'
		);	
			
		if (in_array($sort, $sort_data)) {
			$sql .= " ORDER BY " . $sort;
		} else {
			$sql .= " ORDER BY pd.name";	
		}
			
		if ($order == 'DESC') {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
			
		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getTotalProductsByKeyword($keyword, $description = FALSE) {
		$sql = "SELECT COUNT(*) AS total FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->language->getId() . "'";
		
		if (!$description) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($keyword) . "%'";
		} else {
			$sql .= " AND (pd.name LIKE '%" . $this->db->escape($keyword) . "%' OR pd.description LIKE '%" . $this->db->escape($keyword) . "%')";
		}
		
		$sql .= " AND p.status = '1' AND p.date_available <= NOW()";
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];	
	}
	
	public function getLatestProducts($limit) {
		$product = $this->cache->get('product.latest.' . $this->language->getId() . '.' . $limit);

		if (!$product) { 
			$query = $this->db->query("SELECT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND pd.language_id = '" . (int)$this->language->getId() . "' ORDER BY p.date_added DESC LIMIT " . (int)$limit);
		 	 
			$product = $query->rows;

			$this->cache->set('product.latest.' . $this->language->getId() . '.' . $limit, $product);
		}
		
		return $product;
	}

	public function getPopularProducts($limit) {
		$product = $this->cache->get('product.popular.' . $this->language->getId() . '.' . $limit);

		if (!$product) { 
			$query = $this->db->query("SELECT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE p.status = '1' AND p.date_available <= NOW() AND pd.language_id = '" . (int)$this->language->getId() . "' ORDER BY p.viewed DESC LIMIT " . (int)$limit);
		 	 
			$product = $query->rows;

			$this->cache->set('product.popular.' . $this->language->getId() . '.' . $limit, $product);
		}
		
		return $product;
	}
		
	public function updateViewed($product_id) {
		$this->db->query("UPDATE product SET viewed = viewed + 1 WHERE product_id = '" . (int)$product_id . "'");
	}
		
	public function getProductOptions($product_id) {
		$product_option_data = array();
		
		$product_option = $this->db->query("SELECT * FROM product_option WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order");
		
		foreach ($product_option->rows as $product_option) {
			$product_option_value_data = array();
			
			$product_option_value = $this->db->query("SELECT * FROM product_option_value WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "' ORDER BY sort_order");
			
			foreach ($product_option_value->rows as $product_option_value) {
				$product_option_value_description = $this->db->query("SELECT * FROM product_option_value_description WHERE product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "' AND language_id = '" . (int)$this->language->getId() . "'");
			
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'name'                    => $product_option_value_description->row['name'],
         			'price'                   => $product_option_value['price'],
         			'prefix'                  => $product_option_value['prefix']
				);
			}
			
			$product_option_description = array();
			
			$product_option_description = $this->db->query("SELECT * FROM product_option_description WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "' AND language_id = '" . (int)$this->language->getId() . "'");
						
        	$product_option_data[] = array(
        		'product_option_id' => $product_option['product_option_id'],
				'name'              => $product_option_description->row['name'],
				'option_value'      => $product_option_value_data,
				'sort_order'        => $product_option['sort_order']
        	);
      	}	
		
		return $product_option_data;
	}
	
	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM product_image WHERE product_id = '" . (int)$product_id . "'");

		return $query->rows;		
	}
	
	public function getProductSpecial($product_id) {
		$query = $this->db->query("SELECT * FROM product_special WHERE product_id = '" . (int)$product_id . "' AND date_start < NOW() AND date_end > NOW() LIMIT 1");
		
		if ($query->num_rows) {
			return $query->row['price'];		
		} else {
			return FALSE;
		}
	}
	
	public function getProductSpecials($sort = 'pd.name', $order = 'ASC', $start = 0, $limit = 20) {
		$sql = "SELECT *, pd.name AS name, p.price, ps.price AS special, p.image, m.name AS manufacturer, ss.name AS stock, (SELECT AVG(r.rating) FROM review r WHERE p.product_id = r.product_id GROUP BY r.product_id) AS rating FROM product_special ps LEFT JOIN product p ON (ps.product_id = p.product_id) LEFT JOIN product_description pd ON (p.product_id = pd.product_id) LEFT JOIN manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN stock_status ss ON (p.stock_status_id = ss.stock_status_id) LEFT JOIN product_to_category p2c ON (p.product_id = p2c.product_id) WHERE ps.date_start < NOW() AND ps.date_end > NOW() AND p.status = '1' AND p.date_available <= NOW() AND pd.language_id = '" . (int)$this->language->getId() . "' AND ss.language_id = '" . (int)$this->language->getId() . "'";

		$sort_data = array(
			'pd.name',
			'p.price',
			'rating'
		);	
			
		if (in_array($sort, $sort_data)) {
			$sql .= " ORDER BY " . $sort;
		} else {
			$sql .= " ORDER BY pd.name";	
		}
			
		if ($order == 'DESC') {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
			
		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}	
	
	public function getTotalProductSpecials() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM product_special ps LEFT JOIN product p ON (ps.product_id = p.product_id) WHERE p.status = '1' AND ps.date_start < NOW() AND ps.date_end > NOW()");
		
		return $query->row['total'];
	}	
}
?>