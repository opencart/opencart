<?php

class CatalogModelAccountAddressTest extends OpenCartTest {
	
	/**
	 * @before
	 */
	public function setupTest() {		
		$this->loadModelByRoute('account/address');
		$this->customerLogout();
		$this->emptyTables();
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_id = 1, email = 'customer@localhost', `status` = 1, customer_group_id = 1, date_added = '1970-01-01 00:00:00', ip = '127.0.0.1'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_ip SET ip = '127.0.0.1', customer_id = 1");
		
		$this->customerLogin('customer@localhost', '', true);
	}
	
	/**
	 * @after
	 */
	public function completeTest() {
		$this->emptyTables();
		$this->customerLogout();
	}
	
	private function emptyTables() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_ban_ip");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_ip");
		$this->db->query("DELETE FROM " . DB_PREFIX . "address");
	}
	
	public function testAddAddress() {
		$address = array(
			'firstname' => '',
			'lastname' => '',
			'company' => '',
			'address_1' => '',
			'address_2' => '',
			'postcode' => '',
			'city' => '',
			'zone_id' => 0,
			'country_id' => 0,
			'custom_data' => array(),
			'default' => true,
		);
		
		$addressId = $this->model_account_address->addAddress($address);
		
		$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "address LIMIT")->row;
		
		foreach ($address as $key => $value) {
			$this->assertEquals($value, $address[$key]);
		}
		
		$customer = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = 1")->row;
		$this->assertEquals($addressId, $customer['address_id']);
		
		$address['default'] = false;
		
		$this->model_account_address->addAddress($address);
		$customer = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = 1")->row;
		$this->assertEquals($addressId, $customer['address_id'], 'Changed default address unnecessarily');
	}
}
