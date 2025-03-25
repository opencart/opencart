<?php
namespace Opencart\catalog\Controller\Api;
/**
 * Class Customer
 *
 * Can be loaded using $this->load->controller('api/customer');
 *
 * @package Opencart\Catalog\Controller\Api
 */
class Customer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return array<string, mixed>
	 */
	public function index(): array {
		$this->load->language('api/customer');

		$output = [];

		$required = [
			'customer_id'       => 0,
			'customer_group_id' => 0,
			'firstname'         => '',
			'lastname'          => '',
			'email'             => '',
			'telephone'         => '',
			'custom_field'      => []
		];

		$post_info = $this->request->post + $required;

		// Customer
		if ($post_info['customer_id']) {
			$this->load->model('account/customer');

			$customer_info = $this->model_account_customer->getCustomer($post_info['customer_id']);

			if (!$customer_info) {
				$output['error']['warning'] = $this->language->get('error_customer');
			}
		}

		// Customer Group
		if ($post_info['customer_group_id']) {
			$customer_group_id = (int)$post_info['customer_group_id'];
		} else {
			$customer_group_id = (int)$this->config->get('config_customer_group_id');
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

		if (!$customer_group_info) {
			$output['error']['customer_group'] = $this->language->get('error_customer_group');
		}

		if (!oc_validate_length($post_info['firstname'], 1, 32)) {
			$output['error']['firstname'] = $this->language->get('error_firstname');
		}

		if (!oc_validate_length($post_info['lastname'], 1, 32)) {
			$output['error']['lastname'] = $this->language->get('error_lastname');
		}

		if (!oc_validate_email($post_info['email'])) {
			$output['error']['email'] = $this->language->get('error_email');
		}

		if ($this->config->get('config_telephone_required') && !oc_validate_length($post_info['telephone'], 3, 32)) {
			$output['error']['telephone'] = $this->language->get('error_telephone');
		}

		// Custom fields validation
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields((int)$customer_group_id);

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'account') {
				if ($custom_field['required'] && empty($post_info['custom_field'][$custom_field['custom_field_id']])) {
					$output['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !oc_validate_regex($post_info['custom_field'][$custom_field['custom_field_id']], $custom_field['validation'])) {
					$output['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
				}
			}
		}

		if (!$output) {
			// Log the customer in
			$this->customer->login($post_info['email'], '', true);

			$this->session->data['customer'] = [
				'customer_id'       => $post_info['customer_id'],
				'customer_group_id' => $post_info['customer_group_id'],
				'firstname'         => $post_info['firstname'],
				'lastname'          => $post_info['lastname'],
				'email'             => $post_info['email'],
				'telephone'         => $post_info['telephone'],
				'custom_field'      => $post_info['custom_field'] ?? []
			];

			$output['success'] = $this->language->get('text_success');
		}

		return $output;
	}
}
