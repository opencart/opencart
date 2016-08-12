<?php
// *	@copyright	OPENCART.PRO 2011 - 2015.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ModelCatalogPro extends Model {
	
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
}
?>