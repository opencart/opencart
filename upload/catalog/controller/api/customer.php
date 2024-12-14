<?php
namespace Opencart\catalog\controller\api;
/**
 * Class Customer
 *
 * @package Opencart\Catalog\Controller\Api
 */
class Customer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return array
	 */
	public function index(): array {
		$this->load->language('api/customer');

		$output = [];

		$keys = [
			'customer_id'       => 0,
			'customer_group_id' => 0,
			'firstname'         => '',
			'lastname'          => '',
			'email'             => '',
			'telephone'         => '',
			'custom_field'      => []
		];

		foreach ($keys as $key => $Value) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = $Value;
			}
		}

		// Customer
		if ($this->request->post['customer_id']) {
			$this->load->model('account/customer');

			$customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);

			if (!$customer_info) {
				$output['error']['warning'] = $this->language->get('error_customer');
			}
		}

		// Customer Group
		if ($this->request->post['customer_group_id']) {
			$customer_group_id = (int)$this->request->post['customer_group_id'];
		} else {
			$customer_group_id = (int)$this->config->get('config_customer_group_id');
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

		if (!$customer_group_info) {
			$output['error']['customer_group'] = $this->language->get('error_customer_group');
		}

		if (!oc_validate_length($this->request->post['firstname'], 1, 32)) {
			$output['error']['firstname'] = $this->language->get('error_firstname');
		}

		if (!oc_validate_length($this->request->post['lastname'], 1, 32)) {
			$output['error']['lastname'] = $this->language->get('error_lastname');
		}

		if (!oc_validate_email($this->request->post['email'])) {
			$output['error']['email'] = $this->language->get('error_email');
		}

		if ($this->config->get('config_telephone_required') && !oc_validate_length($this->request->post['telephone'], 3, 32)) {
			$output['error']['telephone'] = $this->language->get('error_telephone');
		}

		// Custom field validation
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields((int)$customer_group_id);

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'account') {
				if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
					$output['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !oc_validate_regex($this->request->post['custom_field'][$custom_field['custom_field_id']], $custom_field['validation'])) {
					$output['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
				}
			}
		}

		if (!$output) {
			// Log the customer in
			$this->customer->login($this->request->post['email'], '', true);

			$this->session->data['customer'] = [
				'customer_id'       => $this->request->post['customer_id'],
				'customer_group_id' => $this->request->post['customer_group_id'],
				'firstname'         => $this->request->post['firstname'],
				'lastname'          => $this->request->post['lastname'],
				'email'             => $this->request->post['email'],
				'telephone'         => $this->request->post['telephone'],
				'custom_field'      => $this->request->post['custom_field'] ?? []
			];

			$output['success'] = $this->language->get('text_success');
		}

		return $output;
	}
}
