<?php
class ControllerApiCustomer extends Controller {
	public function add() {
		$json = array();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$json['customer_id'] = $this->model_account_customer->addCustomer($this->request->post);
		} else {
			$json['error'] = $this->error;
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function edit() {
		$json = array();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$json['customer_id'] = $this->model_account_customer->addCustomer($this->request->post);
		} else {
			$json['error'] = $this->error;
		}		
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function delete() {
		$json = array();
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$json['customer_id'] = $this->model_account_customer->addCustomer($this->request->post);
		} else {
			$json['error'] = $this->error;
		}		
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function getCustomer() {
		$json = array();
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function validate() {
		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_exists');
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		// Customer Group
		if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->post['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		// Custom field validation
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields(array('filter_customer_group_id' => $customer_group_id));

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
				$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
			}
		}

		if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if ($this->request->post['confirm'] != $this->request->post['password']) {
			$this->error['confirm'] = $this->language->get('error_confirm');
		}

		return !$this->error;
		
	}
}