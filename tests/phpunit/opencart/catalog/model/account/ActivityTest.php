<?php

class CatalogModelAccountActivityTest extends OpenCartTest {
	
	/**
	 * @before
	 */
	public function setupTest() {		
		$this->loadModel('account/activity');
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_activity");
	}
	
	/**
	 * @after
	 */
	public function completeTest() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_activity");
	}
	
	public function testAddActivity() {
		$key = 'key';
		$data = array(
			'customer_id' => 0,
		);
		
		$this->request->server['REMOTE_ADDR'] = '127.0.0.1';
		
		$this->model_account_activity->addActivity($key, $data);
		
		$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_activity")->row;
		
		$this->assertEquals($key, $result['key']);
		$this->assertEquals($data, json_decode($result['data'], true));
	}
}
