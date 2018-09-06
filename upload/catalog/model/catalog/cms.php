<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ModelCatalogCms extends Model {
	
	
	
	private $NOW;

	public function __construct($registry) {
		$this->NOW = date('Y-m-d H:i') . ':00';
		parent::__construct($registry);
	}
	
	public function getProductRelatedByCategory($data) {

		$product_data = array();
		
		$this->load->model('catalog/product');
				
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related_wb pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.category_id = '" . (int)$data['category_id'] . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT " . (int)$data['limit']); 

		foreach ($query->rows as $result) { 
			$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
		}
		return $product_data;
	}
	
	public function getProductRelatedByManufacturer($data) {

		$product_data = array();
		
		$this->load->model('catalog/product');
				
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related_mn pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pr.manufacturer_id = '" . (int)$data['manufacturer_id'] . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT " . (int)$data['limit']); 

		foreach ($query->rows as $result) { 
			$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
		}
		return $product_data;
	}
	
	public function getBestSeller($data = array()) {

		$product_data = array();

		$sort_data = array(
			'op.name',
			'p.model',
			'p.price',
			'rating',
			'total'
		);
		$sql="SELECT op.product_id, SUM(op.quantity) AS total,(SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = op.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id";

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(op.name) DESC";
		} else {
			$sql .= " ASC, LCASE(op.name) ASC";
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

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
		}

		return $product_data;
	}
	
	public function getTotalBestSeller() {
		$query = $this->db->query("SELECT COUNT(DISTINCT op.product_id) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
	
	public function getLatest($data = array()) {

		$this->load->model('catalog/product');
		$NOW = date('Y-m-d H:i') . ':00';
		$sql = "SELECT * FROM (SELECT p.product_id, p.sort_order, p.model, pd.name, p.quantity, p.price, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < '" . $NOW . "') AND (pd2.date_end = '0000-00-00' OR pd2.date_end > '" . $NOW . "')) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id  AND ((ps.date_start = '0000-00-00' OR ps.date_start < '" . $NOW . "') AND (ps.date_end = '0000-00-00' OR ps.date_end > '" . $NOW . "')) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special,  (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.status = '1' AND p.date_available <= '" . $NOW . "' AND p2s.store_id = '" . (int)$this->config->get('config_store_id') .  "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY p.product_id ORDER BY p.date_added DESC, LCASE(pd.name) DESC";

		$sql .= " LIMIT  0," . (int)$data['max'];

		$sql .= ") p ORDER BY ";

		$sort_data = array(
			'pd.name',
			'quantity',
			'ps.price',
			'rating',
			'p.sort_order',
			'p.model'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name') {
				$sql .= " LCASE('name')";
			} elseif ($data['sort'] == 'ps.price') {
				$sql .= " (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " " . $data['sort'];
			}
		} else {
			$sql .= " sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(name) DESC";
		} else {
			$sql .= " ASC, LCASE(name) ASC";
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

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
		}

		return $product_data;
	}
	
	public function getMostViewed($data = array()) {
		$this->load->model('catalog/product');
		
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	

		$product_data = $this->cache->get('product.mostviewed.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$data['limit']);

		$product_data = null;
		
		if (!$product_data) { 
			$product_data = array();

		$sql = "SELECT * FROM (SELECT p.product_id, 
		(SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id 
		AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 
		WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' 
		AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < '" . $this->NOW . "') 
		AND (pd2.date_end = '0000-00-00' OR pd2.date_end > '" . $this->NOW . "')) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, 
		(SELECT price FROM " . DB_PREFIX . "product_special ps 
		WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' 
		AND ((ps.date_start = '0000-00-00' OR ps.date_start < '" . $this->NOW . "') AND (ps.date_end = '0000-00-00' OR ps.date_end > '" . $this->NOW . "')) 
		ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, p.sort_order, p.viewed, p.price, p.model"; 
		
		$sql .= " FROM " . DB_PREFIX . "product p  	
		LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) 
		WHERE p.status = '1' AND p.date_available <= '" . $this->NOW . "' 
		AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
		GROUP BY p.product_id  ORDER by p.viewed DESC  LIMIT 0, " . (int)$data['max'];

		$sql .= ") p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id AND pd.language_id = '".  (int)$this->config->get('config_language_id') ."') ORDER BY ";
	
		
		$sort_data = array(
			'pd.name',
			'quantity',
			'ps.price',
			'rating',
			'p.sort_order',
			'p.model',
			'p.viewed'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name') {
				$sql .= " LCASE('pd.name')";
			} elseif ($data['sort'] == 'ps.price') {
				$sql .= " (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " " . $data['sort'];
			}
		} else {
			$sql .= " p.sort_order";	
		}
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(name) DESC";
		} else {
			$sql .= " ASC, LCASE(name) ASC";
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
		
	

			foreach ($query->rows as $result) { 		
				$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
			}

			$this->cache->set('product.mostviewed.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$data['limit'], $product_data);
		}

		return $product_data;
	}
}
?>