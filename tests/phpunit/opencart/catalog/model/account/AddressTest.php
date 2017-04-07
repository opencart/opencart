<?php

class CatalogModelAccountAddressTest extends OpenCartTest {
	
	/**
	 * @before
	 */
	public function setupTest() {		
		$this->loadModel('account/address');
		$this->logout();
		$this->emptyTables();
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_id = 1, email = 'customer@localhost', `status` = 1, customer_group_id = 1, date_added = '1970-01-01 00:00:00', ip = '127.0.0.1'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_ip SET ip = '127.0.0.1', customer_id = 1");
		
		$this->login('customer@localhost', '', true);
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
		
		$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "address")->row;
		
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
	
	public function testEditAddress() {
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
		
		$address = array(
			'firstname' => 'firstname',
			'lastname' => 'lastname',
			'company' => 'company',
			'address_1' => 'address_1',
			'address_2' => 'address_2',
			'postcode' => 'postcode',
			'city' => 'city',
			'zone_id' => 0,
			'country_id' => 0,
			'custom_data' => array(),
			'default' => true,
		);
		
		$this->model_account_address->editAddress($addressId, $address);
		
		$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "address")->row;
		
		foreach ($address as $key => $value) {
			$this->assertEquals($value, $address[$key]);
		}
		
		$addressId = $this->model_account_address->addAddress($address);
		$address['default'] = false;
		$this->model_account_address->editAddress($addressId, $address);
		$customer = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = 1")->row;
		$this->assertEquals($addressId, $customer['address_id'], 'Changed default address unnecessarily');
	}
	
	public function testDeleteAddress() {
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
		
		$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "address")->row;
		$this->assertNotEmpty($result);
		
		$this->model_account_address->deleteAddress($addressId);
		
		$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "address")->row;
		$this->assertEmpty($result);
	}
	
	public function testGetAddress() {
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
		
		$address = $this->model_account_address->getAddress($addressId);
		$this->assertNotFalse($address);
		
		$address = $this->model_account_address->getAddress(0);
		$this->assertFalse($address);
	}
	
	public function testGetAddresses() {
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
		
		for ($i = 0; $i < 5; $i++) {
			$this->model_account_address->addAddress($address);
		}
		
		$addresses = $this->model_account_address->getAddresses();
		$this->assertCount(5, $addresses);
	}
	
	public function testGetTotalAddresses() {
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
		
		for ($i = 0; $i < 5; $i++) {
			$this->model_account_address->addAddress($address);
		}
		
		$addressCount = $this->model_account_address->getTotalAddresses();
		$this->assertEquals(5, $addressCount);
	}
}
