<?php

class CatalogModelAccountCustomerTest extends OpenCartTest {

	/**
	 * @before
	 */
	public function setupTest() {
		$this->loadModelByRoute('account/customer');
		$this->customerLogout();
		$this->emptyTables();

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_id = 1, email = 'customer@localhost', `status` = 1, customer_group_id = 1, date_added = '1970-01-01 00:00:00', ip = '127.0.0.1'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_ip SET ip = '127.0.0.1', customer_id = 1");
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

	

	public function testEditCustomer() {
		$this->customerLogin('customer@localhost', '', true);

		$customerData = array(
			'firstname' => 'firstname',
			'lastname' => 'lastname',
			'email' => 'email',
			'telephone' => 'telephone',
			'fax' => 'fax',
			'custom_field' => array(),
		);

		$this->model_account_customer->editCustomer($customerData);
		$customer = $this->model_account_customer->getCustomer(1);

		$customerData['custom_field'] = serialize(array());

		foreach ($customerData as $key => $value) {
			$this->assertEquals($value, $customer[$key]);
		}
	}

	public function testEditPassword() {
		$this->model_account_customer->editPassword('customer@localhost', 'password');

		$row = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = 1")->row;

		$this->assertNotEmpty($row['password']);
		$this->assertNotEmpty($row['salt']);
	}

	public function testEditNewsletter() {
		$this->customerLogin('customer@localhost', '', true);

		$this->model_account_customer->editNewsletter(1);

		$row = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = 1")->row;

		$this->assertEquals(1, $row['newsletter']);
	}

	public function testGetCustomer() {
		$customer = $this->model_account_customer->getCustomer(1);

		$this->assertNotEmpty($customer);
	}

	public function testGetCustomerByEmail() {
		$customer = $this->model_account_customer->getCustomerByEmail('customer@localhost');

		$this->assertNotEmpty($customer);
	}

	public function testGetCustomerByToken() {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = 'token'");

		$customer = $this->model_account_customer->getCustomerByToken('token');

		$this->assertNotEmpty($customer);
	}

	/*
	public function testGetCustomers() {
		$data = array(
			'filter_name' => '',
			'filter_email' => 'customer@localhost',
			'filter_customer_group_id' => '1',
			'filter_status' => 1,
			'filter_approved' => 0,
			'filter_ip' => '127.0.0.1',
			'filter_date' => '1970-01-01',
			'sort' => 'c.email',
		);

		$customers = $this->model_account_customer->getCustomers($data);

		$this->assertCount(1, $customers);
	}
	*/

	public function testGetTotalCustomersByEmail() {
		$count = $this->model_account_customer->getTotalCustomersByEmail('customer@localhost');

		$this->assertEquals(1, $count);
	}

	public function testGetIps() {
		$ips = $this->model_account_customer->getIps(1);

		$this->assertCount(1, $ips);
	}

	public function testIsBanIp() {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_ban_ip SET ip = '255.255.255.255'");

		$bannedIp = $this->model_account_customer->isBanIp('255.255.255.255');
		$this->assertTrue($bannedIp == true);

		$bannedIp = $this->model_account_customer->isBanIp('0.0.0.0');
		$this->assertFalse($bannedIp == true);
	}

	// Cannot run this test as the model instantiates Mail class which generates an error
	// because it can't send a confirmation email. Need to refactor the code, so the Mail
	// class could be substituted with a mock object.
	/*
	public function testAddCustomer() {
		$customerData = array(
			'firstname' => '',
			'lastname' => '',
			'email' => '',
			'telephone' => '',
			'fax' => '',
			'custom_field' => array(
				'account' => array(),
			),
			'password' => 'password123',
			'newsletter' => 0,
			'approved' => 1,
			'company' => '',
			'address_1' => '',
			'address_2' => '',
			'city' => '',
			'postcode' => '',
			'country_id' => 0,
			'zone_id' => 0,
			'custom_field' => array(
				'address' => array(),
			),
		);

		$this->request->server['REMOTE_ADDR'] = '127.0.0.1';
		$this->config->set('config_account_mail', false);
		$this->config->set('config_mail', array());

		$customerId = $this->model_account_customer->addCustomer($customerData);
	}
	*/

}
