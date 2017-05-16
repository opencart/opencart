<?php

class CatalogModelCheckoutOrderTest extends OpenCartTest {
	
	/**
	 * @before
	 */
	public function setupTest() {
		$this->loadModel('checkout/order');
		$this->loadModel('account/custom_field');
		
		$this->emptyTables();
	}
	
	/**
	 * @after
	 */
	public function completeTest() {
		$this->emptyTables();
	}
	
	private function emptyTables() {
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
	
	private function getOrderArray() {
		$order = array(
			'invoice_prefix' => '',
			'store_id' => 0,
			'store_url' => '',
			'store_name' => '',
			'customer_id' => 0,
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
		
		return $order;
	}
	
	public function testAddOrder() {
		$orderData = $this->getOrderArray();
		
		$orderId = $this->model_checkout_order->addOrder($orderData);
		
		$this->assertNotNull($orderId);
		
		$numRows = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order`")->row['total'];
		$this->assertEquals(1, $numRows);
		
		$numRows = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_product`")->row['total'];
		$this->assertEquals(1, $numRows);
		
		$numRows = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_option`")->row['total'];
		$this->assertEquals(1, $numRows);
		
		$numRows = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_voucher`")->row['total'];
		$this->assertEquals(1, $numRows);
		
		$numRows = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_total`")->row['total'];
		$this->assertEquals(2, $numRows);
	}
	
	// The following three tests should be completed when custom fields are implemented
	
	public function testGetOrder() {
		$this->markTestIncomplete();
		
		$orderData = $this->getOrderArray();
		
		$this->model_checkout_order->addOrder($orderData);
		
		$orderId = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` LIMIT 1")->row['order_id'];
		
		$order = $this->model_checkout_order->getOrder($orderId);
		
		$this->assertEquals($orderId, $order['order_id']);
	}
	
	public function testConfirm() {
		$this->markTestIncomplete();
		
		$orderData = $this->getOrderArray();
		
		$orderId = $this->model_checkout_order->addOrder($orderData);
		
		$this->model_checkout_order->confirm($orderId, $this->config->get('config_complete_status_id'));
	}

	public function testUpdate() {
		$this->markTestIncomplete();
		
		$orderData = $this->getOrderArray();
		
		$orderId = $this->model_checkout_order->addOrder($orderData);
		$this->model_checkout_order->update($orderId, $this->config->get('config_complete_status_id'));
	}
}
