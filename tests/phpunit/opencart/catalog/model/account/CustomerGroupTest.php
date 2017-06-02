<?php

class CatalogModelAccountCustomerGroupTest extends OpenCartTest {
	
	/**
	 * @before
	 */
	public function setupTest() {		
		$this->loadModel('account/customer_group');
	}
	
	public function testGetCustomerGroup() {
		$result = $this->model_account_customer_group->getCustomerGroup(1);
		$this->assertNotEmpty($result);
	}
	
	public function testGetCustomerGroups() {
		$result = $this->model_account_customer_group->getCustomerGroups();
		$this->assertCount(1, $result);
	}
}
