<?php

class CatalogModelAccountOrderTest extends OpenCartTest {
	
	/**
	 * @before
	 */
	public function setupTest() {		
		$this->loadModel('account/order');
		$this->loadModel('account/custom_field');
		$this->loadModel('checkout/order');

		$this->logout();
		$this->emptyTables();

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_id = 1, email = 'customer@localhost', `status` = 1, customer_group_id = 1, date_added = '1970-01-01 00:00:00', ip = '127.0.0.1'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_ip SET ip = '127.0.0.1', customer_id = 1");

		$this->login('customer@localhost', '', true);

		$this->addDummyOrder();
	}

	/**
	 * @after
	 */
	public function completeTest() {
		$this->emptyTables();
		$this->logout();
	}

	private function emptyTables() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_ip");
		$this->db->query("DELETE FROM " . DB_PREFIX . "address");
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "order");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_custom_field");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_history");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_option");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_product");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_recurring");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_recurring_transaction");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_total");
		$this->db->query("DELETE FROM " . DB_PREFIX . "order_voucher");
	}
	
	private function addDummyOrder() {
		$order = array(
			'invoice_prefix' => '',
			'store_id' => 0,
			'store_url' => '',
			'store_name' => '',
			'customer_id' => $this->customer->getId(),
			'customer_group_id' => 0,
			'firstname' => '',
			'lastname' => '',
			'email' => '',
			'telephone' => '',
			'fax' => '',
			'custom_field' => array(),
			'payment_firstname' => '',
			'payment_lastname' => '',
			'payment_company' => '',
			'payment_address_1' => '',
			'payment_address_2' => '',
			'payment_city' => '',
			'payment_postcode' => '',
			'payment_zone' => '',
			'payment_zone_id' => 0,
			'payment_country' => '',
			'payment_country_id' => 0,
			'payment_address_format' => '',
			'payment_custom_field' => array(),
			'payment_method' => '',
			'payment_code' => '',
			'shipping_firstname' => '',
			'shipping_lastname' => '',
			'shipping_company' => '',
			'shipping_address_1' => '',
			'shipping_address_2' => '',
			'shipping_city' => '',
			'shipping_postcode' => '',
			'shipping_zone' => '',
			'shipping_zone_id' => 0,
			'shipping_country' => '',
			'shipping_country_id' => 0,
			'shipping_address_format' => '',
			'shipping_custom_field' => array(),
			'shipping_method' => '',
			'shipping_code' => '',
			'products' => array(
				array(
					'product_id' => 0,
					'name' => '',
					'model' => '',
					'quantity' => 0,
					'price' => 0.00,
					'total' => 0.00,
					'tax' => 0.00,
					'reward' => 0.00,
					'option' => array(
						array(
							'product_option_id' => 0,
							'product_option_value_id' => 0,
							'name' => '',
							'value' => '',
							'type' => '',
						),
					)
				),
			),
			'vouchers' => array(
				array(
					'description' => '',
					'code' => '',
					'from_name' => '',
					'from_email' => '',
					'to_name' => '',
					'to_email' => '',
					'voucher_theme_id' => 0,
					'message' => '',
					'amount' => 0.00,
				),
			),
			'comment' => '',
			'total' => '',
			'affiliate_id' => 0,
			'commission' => 0,
			'marketing_id' => 0,
			'tracking' => '',
			'language_id' => 0,
			'currency_id' => 0,
			'currency_code' => '',
			'currency_value' => 0,
			'ip' => '',
			'forwarded_ip' => '',
			'user_agent' => '',
			'accept_language' => '',	
			'totals' => array(
				array(
					'code' => '',
					'title' => '',
					'value' => 0.00,
					'sort_order' => 0,
				),
				array(
					'code' => '',
					'title' => '',
					'value' => 0.00,
					'sort_order' => 0,
				),
			),
		);
		
		$orderId = $this->model_checkout_order->addOrder($order);
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = 1 WHERE order_id = $orderId");
	}
	
	public function testGetOrder() {
		$result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order`")->row;
		
		$order = $this->model_account_order->getOrder($result['order_id']);
		$this->assertNotEmpty($order);
		
		$order = $this->model_account_order->getOrder(0);
		$this->assertFalse($order);
	}
	
	public function testGetOrders() {
		for ($i = 0; $i < 5; $i++) {
			$this->addDummyOrder();
		}
		
		$orders = $this->model_account_order->getOrders();
		$this->assertCount(6, $orders);
	}
	
	public function testGetOrderProducts() {
		$orderId = $this->db->query("SELECT order_id FROM `". DB_PREFIX . "order` LIMIT 1")->row['order_id'];
		
		$products = $this->model_account_order->getOrderProducts($orderId);
		$this->assertCount(1, $products);
	}
	
	public function testGetOrderProduct() {
		$orderId = $this->db->query("SELECT order_id FROM `". DB_PREFIX . "order` LIMIT 1")->row['order_id'];
		
		$products = $this->model_account_order->getOrderProducts($orderId);
		$this->assertCount(1, $products);
		
		$product = $this->model_account_order->getOrderProduct($orderId, $products[0]['order_product_id']);
		$this->assertNotEmpty($product);
	}
	
	public function testGetOrderOptions() {
		$orderId = $this->db->query("SELECT order_id FROM `". DB_PREFIX . "order` LIMIT 1")->row['order_id'];
		
		$products = $this->model_account_order->getOrderProducts($orderId);
		$this->assertCount(1, $products);
		
		$product = $this->model_account_order->getOrderProduct($orderId, $products[0]['order_product_id']);
		$this->assertNotEmpty($product);
		
		$options = $this->model_account_order->getOrderOptions($orderId, $product['order_product_id']);
		$this->assertNotEmpty($options);
	}
	
	public function testGetOrderVouchers() {
		$orderId = $this->db->query("SELECT order_id FROM `". DB_PREFIX . "order` LIMIT 1")->row['order_id'];
		
		$vouchers = $this->model_account_order->getOrderVouchers($orderId);
		$this->assertCount(1, $vouchers);
	}
	
	public function testGetOrderTotals() {
		$orderId = $this->db->query("SELECT order_id FROM `". DB_PREFIX . "order` LIMIT 1")->row['order_id'];
		
		$totals = $this->model_account_order->getOrderTotals($orderId);
		$this->assertCount(2, $totals);
	}
	
	public function testGetOrderHistories() {
		$orderId = $this->db->query("SELECT order_id FROM `". DB_PREFIX . "order` LIMIT 1")->row['order_id'];
	
		for ($i = 0; $i < 5; $i++) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_history` SET order_id = $orderId, order_status_id = 1, notify = 1, comment = '', date_added = '1970-01-01 00:00:00'");
		}
		
		$histories = $this->model_account_order->getOrderHistories($orderId);

		$this->assertCount(5, $histories);
	}
	
	public function testGetTotalOrders() {
		$total = $this->model_account_order->getTotalOrders();
		
		$this->assertEquals(1, $total);
	}
	
	public function testGetTotalOrderProductsByOrderId() {
		$orderId = $this->db->query("SELECT order_id FROM `". DB_PREFIX . "order` LIMIT 1")->row['order_id'];
		
		$total = $this->model_account_order->getTotalOrderProductsByOrderId($orderId);
		
		$this->assertEquals(1, $total);
	}
	
	public function testGetTotalOrderVouchersByOrderId() {
		$orderId = $this->db->query("SELECT order_id FROM `". DB_PREFIX . "order` LIMIT 1")->row['order_id'];
		
		$total = $this->model_account_order->getTotalOrderVouchersByOrderId($orderId);
		
		$this->assertEquals(1, $total);
	}
}
