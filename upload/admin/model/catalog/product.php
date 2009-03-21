<?php
class ModelCatalogProduct extends Model {
	public function addProduct($data) {
		$this->db->query("INSERT INTO product SET model = '" . $this->db->escape(@$data['model']) . "', image = '" . $this->db->escape(basename($data['image'])) . "', quantity = '" . (int)@$data['quantity'] . "', stock_status_id = '" . (int)@$data['stock_status_id'] . "', date_available = '" . $this->db->escape(@$data['date_available']) . "', manufacturer_id = '" . (int)@$data['manufacturer_id'] . "', shipping = '" . (int)@$data['shipping'] . "', price = '" . (float)@$data['price'] . "', sort_order = '" . (int)@$data['sort_order'] . "', weight = '" . (float)@$data['weight'] . "', weight_class_id = '" . (int)@$data['weight_class_id'] . "', status = '" . (int)@$data['status'] . "', tax_class_id = '" . (int)@$data['tax_class_id'] . "', date_added = NOW()");
		
		$product_id = $this->db->getLastId();
		
		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape(@$value['name']) . "', meta_description = '" . $this->db->escape(@$value['meta_description']) . "', description = '" . $this->db->escape(@$value['description']) . "'");
		}
		
		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				$this->db->query("INSERT INTO product_option SET product_id = '" . (int)$product_id . "', sort_order = '" . (int)$product_option['sort_order'] . "'");
				
				$product_option_id = $this->db->getLastId();
				
				foreach ($product_option['language'] as $language_id => $language) {
					$this->db->query("INSERT INTO product_option_description SET product_option_id = '" . (int)$product_option_id . "', language_id = '" . (int)$language_id . "', product_id = '" . (int)$product_id . "', name = '" . $this->db->escape($language['name']) . "'");
				}				
				
				if (isset($product_option['product_option_value'])) {
					foreach ($product_option['product_option_value'] as $product_option_value) {
						$this->db->query("INSERT INTO product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', price = '" . (float)$product_option_value['price'] . "', prefix = '" . $this->db->escape($product_option_value['prefix']) . "', sort_order = '" . (int)$product_option_value['sort_order'] . "'");
				
						$product_option_value_id = $this->db->getLastId();
				
						foreach ($product_option_value['language'] as $language_id => $language) {
							$this->db->query("INSERT INTO product_option_value_description SET product_option_value_id = '" . (int)$product_option_value_id . "', language_id = '" . (int)$language_id . "', product_id = '" . (int)$product_id . "', name = '" . $this->db->escape($language['name']) . "'");
						}					
					}
				}
			}
		}
		
		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $value) {
				$this->db->query("INSERT INTO product_discount SET product_id = '" . (int)$product_id . "', quantity = '" . (int)$value['quantity'] . "', discount = '" . (float)$value['discount'] . "'");
			}
		}
		
		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $image) {
				if ($image) {
        			$this->db->query("INSERT INTO product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(basename($image)) . "'");
				}
			}
		}
		
		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}
		}
		
		$this->cache->delete('product');
	}
	
	public function editProduct($product_id, $data) {
		$this->db->query("UPDATE product SET model = '" . $this->db->escape(@$data['model']) . "', image = '" . $this->db->escape(basename($data['image'])) . "', quantity = '" . (int)@$data['quantity'] . "', stock_status_id = '" . (int)@$data['stock_status_id'] . "', date_available = '" . $this->db->escape(@$data['date_available']) . "', manufacturer_id = '" . (int)@$data['manufacturer_id'] . "', shipping = '" . (int)@$data['shipping'] . "', price = '" . (float)@$data['price'] . "', sort_order = '" . (int)@$data['sort_order'] . "', weight = '" . (float)@$data['weight'] . "', weight_class_id = '" . (int)@$data['weight_class_id'] . "', status = '" . (int)@$data['status'] . "', tax_class_id = '" . (int)@$data['tax_class_id'] . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");
		
		$this->db->query("DELETE FROM product_description WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($data['product_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO product_description SET product_id = '" . (int)$product_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape(@$value['name']) . "', meta_description = '" . $this->db->escape(@$value['meta_description']) . "', description = '" . $this->db->escape(@$value['description']) . "'");
		}
		
		$this->db->query("DELETE FROM product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_option_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_option_value_description WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				$this->db->query("INSERT INTO product_option SET product_id = '" . (int)$product_id . "', sort_order = '" . (int)$product_option['sort_order'] . "'");
				
				$product_option_id = $this->db->getLastId();
				
				foreach ($product_option['language'] as $language_id => $language) {
					$this->db->query("INSERT INTO product_option_description SET product_option_id = '" . (int)$product_option_id . "', language_id = '" . (int)$language_id . "', product_id = '" . (int)$product_id . "', name = '" . $this->db->escape($language['name']) . "'");
				}				
				
				if (isset($product_option['product_option_value'])) {
					foreach ($product_option['product_option_value'] as $product_option_value) {
						$this->db->query("INSERT INTO product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', price = '" . (float)$product_option_value['price'] . "', prefix = '" . $this->db->escape($product_option_value['prefix']) . "', sort_order = '" . (int)$product_option_value['sort_order'] . "'");
				
						$product_option_value_id = $this->db->getLastId();
				
						foreach ($product_option_value['language'] as $language_id => $language) {
							$this->db->query("INSERT INTO product_option_value_description SET product_option_value_id = '" . (int)$product_option_value_id . "', language_id = '" . (int)$language_id . "', product_id = '" . (int)$product_id . "', name = '" . $this->db->escape($language['name']) . "'");
						}					
					}
				}
			}
		}
		
		$this->db->query("DELETE FROM product_discount WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $value) {
				$this->db->query("INSERT INTO product_discount SET product_id = '" . (int)$product_id . "', quantity = '" . (int)$value['quantity'] . "', discount = '" . (float)$value['discount'] . "'");
			}
		}
		
		$this->db->query("DELETE FROM product_image WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_image'])) {
			foreach ($data['product_image'] as $image) {
				if ($image) {
        			$this->db->query("INSERT INTO product_image SET product_id = '" . (int)$product_id . "', image = '" . $this->db->escape(basename($image)) . "'");
				}
			}
		}
		
		$this->db->query("DELETE FROM product_to_download WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_download'])) {
			foreach ($data['product_download'] as $download_id) {
				$this->db->query("INSERT INTO product_to_download SET product_id = '" . (int)$product_id . "', download_id = '" . (int)$download_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");
		
		if (isset($data['product_category'])) {
			foreach ($data['product_category'] as $category_id) {
				$this->db->query("INSERT INTO product_to_category SET product_id = '" . (int)$product_id . "', category_id = '" . (int)$category_id . "'");
			}		
		}
		
		$this->cache->delete('product');
	}

	public function deleteProduct($product_id) {
		$this->db->query("DELETE FROM product WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_option_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_option_value_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_discount WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_image WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM review WHERE product_id = '" . (int)$product_id . "'");
		
		$this->cache->delete('product');
	}
	
	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM product WHERE product_id = '" . (int)$product_id . "'");
				
		return $query->row;
	}
	
	public function getProducts($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->language->getId() . "'"; 
		
			if (isset($data['name'])) {
				$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['name']) . "%'";
			}

			if (isset($data['model'])) {
				$sql .= " AND p.model LIKE '%" . $this->db->escape($data['model']) . "%'";
			}

			if (isset($data['status'])) {
				$sql .= " AND p.status = '" . (int)$data['status'] . "'";
			}

			$sort_data = array(
				'pd.name',
				'p.model',
				'p.status',
				'p.sort_order'
			);	
			
			if (in_array(@$data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY pd.name";	
			}
			
			if (@$data['order'] == 'DESC') {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
			
			if (isset($data['start']) || isset($data['limit'])) {
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}	
			
			$query = $this->db->query($sql);
		
			return $query->rows;
		} else {
			$product = $this->cache->get('product.' . $this->language->getId());
		
			if (!$product) {
				$query = $this->db->query("SELECT * FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->language->getId() . "' ORDER BY pd.name ASC");
	
				$product = $query->rows;
			
				$this->cache->set('product.' . $this->language->getId(), $product);
			}	
	
			return $product;
		}
	}
				
	public function getProductDescriptions($product_id) {
		$product_description_data = array();
		
		$query = $this->db->query("SELECT * FROM product_description WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_description' => $result['meta_description'],
				'description'      => $result['description']
			);
		}
		
		return $product_description_data;
	}
	
	public function getProductOptions($product_id) {
		$product_option_data = array();
		
		$product_option = $this->db->query("SELECT * FROM product_option WHERE product_id = '" . (int)$product_id . "' ORDER BY sort_order");
		
		foreach ($product_option->rows as $product_option) {
			$product_option_value_data = array();
			
			$product_option_value = $this->db->query("SELECT * FROM product_option_value WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "' ORDER BY sort_order");
			
			foreach ($product_option_value->rows as $product_option_value) {
				$product_option_value_description_data = array();
				
				$product_option_value_description = $this->db->query("SELECT * FROM product_option_value_description WHERE product_option_value_id = '" . (int)$product_option_value['product_option_value_id'] . "'");

				foreach ($product_option_value_description->rows as $result) {
					$product_option_value_description_data[$result['language_id']] = array('name' => $result['name']);
				}
			
				$product_option_value_data[] = array(
					'product_option_value_id' => $product_option_value['product_option_value_id'],
					'language'                => $product_option_value_description_data,
         			'price'                   => $product_option_value['price'],
         			'prefix'                  => $product_option_value['prefix'],
					'sort_order'              => $product_option_value['sort_order']
				);
			}
			
			$product_option_description_data = array();
			
			$product_option_description = $this->db->query("SELECT * FROM product_option_description WHERE product_option_id = '" . (int)$product_option['product_option_id'] . "'");

			foreach ($product_option_description->rows as $result) {
				$product_option_description_data[$result['language_id']] = array('name' => $result['name']);
			}
		
        	$product_option_data[] = array(
        		'product_option_id'    => $product_option['product_option_id'],
				'language'             => $product_option_description_data,
				'product_option_value' => $product_option_value_data,
				'sort_order'           => $product_option['sort_order']
        	);
      	}	
		
		return $product_option_data;
	}
	
	public function getProductImages($product_id) {
		$product_image_data = array();
		
		$query = $this->db->query("SELECT * FROM product_image WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_image_data[] = $result['image'];
		}
		
		return $product_image_data;
	}
	
	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM product_discount WHERE product_id = '" . (int)$product_id . "'");
		
		return $query->rows;
	}
		
	public function getProductDownloads($product_id) {
		$product_download_data = array();
		
		$query = $this->db->query("SELECT * FROM product_to_download WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_download_data[] = $result['download_id'];
		}
		
		return $product_download_data;
	}

	public function getProductCategories($product_id) {
		$product_category_data = array();
		
		$query = $this->db->query("SELECT * FROM product_to_category WHERE product_id = '" . (int)$product_id . "'");
		
		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}

		return $product_category_data;
	}
	
	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM product p LEFT JOIN product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->language->getId() . "'";
		
		if (isset($data['name'])) {
			$sql .= " AND pd.name LIKE '%" . $this->db->escape($data['name']) . "%'";
		}

		if (isset($data['model'])) {
			$sql .= " AND p.model LIKE '%" . $this->db->escape($data['model']) . "%'";
		}

		if (isset($data['status'])) {
			$sql .= " AND p.status = '" . (int)$data['status'] . "'";
		}

		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}	
	
	public function getTotalProductsByStockStatusId($stock_status_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM product WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalProductsByImageId($image_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM product WHERE image_id = '" . (int)$image_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalProductsByTaxClassId($tax_class_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM product WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalProductsByWeightClassId($weight_class_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM product WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByOptionId($option_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM product_to_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}

	public function getTotalProductsByDownloadId($download_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM product_to_download WHERE download_id = '" . (int)$download_id . "'");
		
		return $query->row['total'];
	}
	
	public function getTotalProductsByManufacturerId($manufacturer_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}
}
?>