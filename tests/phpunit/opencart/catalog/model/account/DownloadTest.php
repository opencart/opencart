<?php

class CatalogModelAccountDownloadTest extends OpenCartTest {

	/**
	 * @before
	 */
	public function setupTest() {
		$this->loadModel('checkout/order');
		$this->loadModel('account/custom_field');
		$this->loadModel('account/download');

		$this->logout();
		$this->emptyTables();

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_id = 1, email = 'customer@localhost', `status` = 1, customer_group_id = 1, date_added = '1970-01-01 00:00:00', ip = '127.0.0.1'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_ip SET ip = '127.0.0.1', customer_id = 1");

		$this->login('customer@localhost', '', true);

		for ($i = 0; $i < 5; $i++) {
			$this->addDummyOrder();
		}

		$this->db->query("INSERT INTO ". DB_PREFIX . "download SET filename = '', mask = '', date_added = '1970-01-01 00:00:00'");
		$downloadId = $this->db->getLastId();
		$this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET download_id = $downloadId, language_id = 1, `name` = ''");
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_to_download SET product_id = 1, download_id = $downloadId");
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
		$this->db->query("DELETE FROM " . DB_PREFIX . "download");
		$this->db->query("DELETE FROM " . DB_PREFIX . "download_description");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download");
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
					'product_id' => 1,
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

		$order_id = $this->model_checkout_order->addOrder($order);
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = " . (int)$this->config->get('config_complete_status_id') . " WHERE order_id = " . (int)$order_id);
	}

	public function testGetDownload() {
		$downloadId = $this->db->query("SELECT download_id FROM `". DB_PREFIX . "download` ORDER BY download_id ASC LIMIT 1")->row['download_id'];

		$download = $this->model_account_download->getDownload($downloadId);

		$this->assertEmpty($download);
	}

	public function testGetDownloads() {
		$downloads = $this->model_account_download->getDownloads();

		$this->assertCount(0, $downloads);
	}

	public function testGetTotalDownloads() {
		$downloads = $this->model_account_download->getTotalDownloads();

		$this->assertEquals(0, $downloads);
	}
}
