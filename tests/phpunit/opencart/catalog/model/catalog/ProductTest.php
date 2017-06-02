<?php

class CatalogModelCatalogProductTest extends OpenCartTest {
	
	/**
	 * @before
	 */
	public function setupTest() {
		$this->loadModel('catalog/product');
	}
	
	public function testGetProduct() {		
		$product = array(
			'product_id' => 28,
			'name' => 'HTC Touch HD',
			'model' => 'Product 1',
			'quantity' => 939,
			'stock_status' => 'In Stock',
			'image' => 'catalog/demo/htc_touch_hd_1.jpg',
			'manufacturer_id' => 5,
			'manufacturer' => 'HTC',
			'price' => '100.00',
		);
		
		$result = $this->model_catalog_product->getProduct($product['product_id']);
		
		$this->assertNotFalse($result, 'Could not retrieve product');
		
		foreach ($product as $key => $value) {
			$this->assertEquals($product[$key], $result[$key]);
		}
	}
	
	public function testNoProduct() {
		$result = $this->model_catalog_product->getProduct(0);
		
		$this->assertFalse($result);
	}
	
	public function testAvailableDate() {
		$product = $this->model_catalog_product->getProduct(28);
		
		$this->db->query("UPDATE " . DB_PREFIX . "product SET date_available = '9999-12-30' WHERE product_id = 28");
		
		$result = $this->model_catalog_product->getProduct(28);
		
		$this->db->query("UPDATE " . DB_PREFIX . "product SET date_available = '" . $product['date_available'] . "' WHERE product_id = 28");
		
		$this->assertFalse($result);
	}
	
	public function testProductViewed() {
		$product = $this->model_catalog_product->getProduct(28);
		$this->model_catalog_product->updateViewed($product['product_id']);
		
		$product2 = $this->model_catalog_product->getProduct(28);
		
		$this->assertEquals($product['viewed'] + 1, $product2['viewed']);
	}
	
	public function testGetProducts() {
		$filters = array(
			'filter_name' => 'a',
			'start' => 0,
			'limit' => 5,
			'sort' => 'p.date_added'
		);
		
		$products = $this->model_catalog_product->getProducts($filters);
		
		$productIds = array(29, 30, 33, 36, 41,);

		$this->assertTrue($productIds === array_keys($products), 'Could not retrieve products');
	}
	
}
