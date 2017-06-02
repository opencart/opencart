<?php

class CatalogModelAccountCustomerTest extends OpenCartTest {

	/**
	 * @before
	 */
	public function setupTest() {
		$this->loadModel('account/customer');
		$this->logout();
		$this->emptyTables();

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_id = 1, email = 'customer@localhost', `status` = 1, customer_group_id = 1, date_added = '1970-01-01 00:00:00', ip = '127.0.0.1'");
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_ip SET ip = '127.0.0.1', customer_id = 1");
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

	public function testEditCustomer() {
		$this->login('customer@localhost', '', true);

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

		$customerData['custom_field'] = '[]';

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
		$this->login('customer@localhost', '', true);

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

	public function testGetTotalCustomersByEmail() {
		$count = $this->model_account_customer->getTotalCustomersByEmail('customer@localhost');

		$this->assertEquals(1, $count);
	}

	public function testGetIps() {
		$ips = $this->model_account_customer->getIps(1);

		$this->assertCount(1, $ips);
	}
}
