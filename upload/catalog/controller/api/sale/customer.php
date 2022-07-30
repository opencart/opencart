<?php
namespace Opencart\Catalog\Controller\Api\Sale;
use \Opencart\System\Helper as Helper;
class Customer extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/sale/customer');

		$json = [];

		$keys = [
			'customer_id',
			'customer_group_id',
			'firstname',
			'lastname',
			'email',
			'telephone',
			'account_custom_field'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		$this->load->model('account/customer');

		if ($this->request->post['customer_id']) {
			$customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);

			if (!$customer_info) {
				$json['error']['warning'] = $this->language->get('error_customer');
			}
		}

		// Customer Group
		if ($this->request->post['customer_group_id']) {
			$customer_group_id = (int)$this->request->post['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

		if (!$customer_group_info) {
			$json['error']['warning'] = $this->language->get('error_customer_group');
		}

		if ((Helper\Utf8\strlen($this->request->post['firstname']) < 1) || (Helper\Utf8\strlen($this->request->post['firstname']) > 32)) {
			$json['error']['firstname'] = $this->language->get('error_firstname');
		}

		if ((Helper\Utf8\strlen($this->request->post['lastname']) < 1) || (Helper\Utf8\strlen($this->request->post['lastname']) > 32)) {
			$json['error']['lastname'] = $this->language->get('error_lastname');
		}

		if ((Helper\Utf8\strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$json['error']['email'] = $this->language->get('error_email');
		}

		if ($this->config->get('config_telephone_required') && (Helper\Utf8\strlen($this->request->post['telephone']) < 3) || (Helper\Utf8\strlen($this->request->post['telephone']) > 32)) {
			$json['error']['telephone'] = $this->language->get('error_telephone');
		}

		// Custom field validation
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields((int)$customer_group_id);

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'account') {
				if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
					$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !preg_match(html_entity_decode($custom_field['validation'], ENT_QUOTES, 'UTF-8'), $this->request->post['custom_field'][$custom_field['custom_field_id']])) {
					$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
				}
			}
		}

		if (!$json) {
			$this->session->data['customer'] = [
				'customer_id'       => $this->request->post['customer_id'],
				'customer_group_id' => $this->request->post['customer_group_id'],
				'firstname'         => $this->request->post['firstname'],
				'lastname'          => $this->request->post['lastname'],
				'email'             => $this->request->post['email'],
				'telephone'         => $this->request->post['telephone'],
				'custom_field'      => !empty($this->request->post['custom_field']) && is_array($this->request->post['custom_field']) ? $this->request->post['custom_field'] : []
			];

			$json['success'] = $this->language->get('text_success');

			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
