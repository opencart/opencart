<?php
namespace Opencart\Admin\Controller\Customer;
/**
 * Class Address
 *
 * Can be loaded using $this->load->controller('customer/address');
 *
 * @package Opencart\Admin\Controller\Customer
 */
class Address extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('customer/customer');

		$this->response->setOutput($this->getAddress());
	}

	/**
	 * Get Address
	 *
	 * @return string
	 */
	public function getAddress(): string {
		$this->load->language('customer/customer');

		if (isset($this->request->get['customer_id'])) {
			$customer_id = (int)$this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		$data['action'] = $this->url->link('customer/address', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $customer_id);

		$data['addresses'] = [];

		// Addresses
		$this->load->model('customer/customer');

		$results = $this->model_customer_customer->getAddresses($customer_id);

		foreach ($results as $result) {
			$data['addresses'][] = [
				'edit'   => $this->url->link('customer/address.form', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $customer_id . '&address_id=' . $result['address_id']),
				'delete' => $this->url->link('customer/address.delete', 'user_token=' . $this->session->data['user_token'] . '&address_id=' . $result['address_id'])
			] + $result;
		}

		$data['address_add'] = $this->url->link('customer/address.form', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $customer_id);

		return $this->load->view('customer/address_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('customer/customer');

		if (isset($this->request->get['customer_id'])) {
			$customer_id = (int)$this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (!isset($this->request->get['address_id'])) {
			$data['heading_title'] = $this->language->get('text_address_add');
		} else {
			$data['heading_title'] = $this->language->get('text_address_edit');
		}

		$data['error_upload_size'] = sprintf($this->language->get('error_upload_size'), $this->config->get('config_file_max_size'));

		$data['save'] = $this->url->link('customer/address.save', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $customer_id);
		$data['action'] = $this->url->link('customer/address', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $customer_id);
		$data['upload'] = $this->url->link('tool/upload.upload', 'user_token=' . $this->session->data['user_token']);

		$data['config_file_max_size'] = ((int)$this->config->get('config_file_max_size') * 1024 * 1024);

		// Customer
		if (isset($this->request->get['address_id'])) {
			$this->load->model('customer/customer');

			$address_info = $this->model_customer_customer->getAddress($this->request->get['address_id']);
		}

		if (!empty($address_info)) {
			$data['address_id'] = $address_info['address_id'];
		} else {
			$data['address_id'] = 0;
		}

		if (!empty($address_info)) {
			$data['firstname'] = $address_info['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (!empty($address_info)) {
			$data['lastname'] = $address_info['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (!empty($address_info)) {
			$data['company'] = $address_info['company'];
		} else {
			$data['company'] = '';
		}

		if (!empty($address_info)) {
			$data['address_1'] = $address_info['address_1'];
		} else {
			$data['address_1'] = '';
		}

		if (!empty($address_info)) {
			$data['address_2'] = $address_info['address_2'];
		} else {
			$data['address_2'] = '';
		}

		if (!empty($address_info)) {
			$data['postcode'] = $address_info['postcode'];
		} else {
			$data['postcode'] = '';
		}

		if (!empty($address_info)) {
			$data['city'] = $address_info['city'];
		} else {
			$data['city'] = '';
		}

		if (!empty($address_info)) {
			$data['country_id'] = $address_info['country_id'];
		} else {
			$data['country_id'] = (int)$this->config->get('config_country_id');
		}

		if (!empty($address_info)) {
			$data['zone_id'] = $address_info['zone_id'];
		} else {
			$data['zone_id'] = 0;
		}

		// Custom Fields
		$data['custom_fields'] = [];

		$filter_data = [
			'filter_location' => 'address',
			'filter_status'   => '1',
			'sort'            => 'cf.sort_order',
			'order'           => 'ASC'
		];

		$this->load->model('customer/custom_field');

		$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

		foreach ($custom_fields as $custom_field) {
			$data['custom_fields'][] = ['custom_field_value' => $this->model_customer_custom_field->getValues($custom_field['custom_field_id'])] + $custom_field;
		}

		if (!empty($address_info)) {
			$data['address_custom_field'] = $address_info['custom_field'];
		} else {
			$data['address_custom_field'] = [];
		}

		if (!empty($address_info)) {
			$data['default'] = $address_info['default'];
		} else {
			$data['default'] = true;
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view('customer/address_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('customer/customer');

		$json = [];

		if (isset($this->request->get['customer_id'])) {
			$customer_id = (int)$this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'customer/customer')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'address_id'   => 0,
			'firstname'    => '',
			'lastname'     => '',
			'company'      => '',
			'address_1'    => '',
			'address_2'    => '',
			'city'         => '',
			'postcode'     => '',
			'country_id'   => 1,
			'zone_id'      => 1,
			'custom_field' => [],
			'default'      => 0
		];

		$post_info = $this->request->post + $required;

		// Customer
		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_customer->getCustomer($customer_id);

		if (!$customer_info) {
			$json['error']['warning'] = $this->language->get('error_customer');
		}

		if (!$json) {
			if (!oc_validate_length($post_info['firstname'], 1, 32)) {
				$json['error']['address_firstname'] = $this->language->get('error_firstname');
			}

			if (!oc_validate_length($post_info['lastname'], 1, 32)) {
				$json['error']['address_lastname'] = $this->language->get('error_lastname');
			}

			if (!oc_validate_length($post_info['address_1'], 3, 128)) {
				$json['error']['address_address_1'] = $this->language->get('error_address_1');
			}

			if (!oc_validate_length($post_info['city'], 2, 128)) {
				$json['error']['address_city'] = $this->language->get('error_city');
			}

			// Country
			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry((int)$post_info['country_id']);

			if ($country_info && $country_info['postcode_required'] && !oc_validate_length($post_info['postcode'], 2, 10)) {
				$json['error']['address_postcode'] = $this->language->get('error_postcode');
			}

			if (!$country_info) {
				$json['error']['address_country'] = $this->language->get('error_country');
			}

			// Zones
			$this->load->model('localisation/zone');

			// Total Zones
			$zone_total = $this->model_localisation_zone->getTotalZonesByCountryId((int)$post_info['country_id']);

			if ($zone_total && !$post_info['zone_id']) {
				$json['error']['address_zone'] = $this->language->get('error_zone');
			}

			// Custom Fields
			$filter_data = [
				'filter_location'          => 'address',
				'filter_customer_group_id' => $customer_info['customer_group_id'],
				'filter_status'            => 1
			];

			$this->load->model('customer/custom_field');

			$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['required'] && empty($post_info['custom_field'][$custom_field['custom_field_id']])) {
					$json['error']['address_custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				} elseif ($custom_field['type'] == 'text' && !empty($custom_field['validation']) && !oc_validate_regex($post_info['custom_field'][$custom_field['custom_field_id']], $custom_field['validation'])) {
					$json['error']['address_custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
				}
			}
		}

		if (!$json) {
			// Customer
			$this->load->model('customer/customer');

			if (!$post_info['address_id']) {
				$this->model_customer_customer->addAddress($customer_id, $post_info);
			} else {
				$this->model_customer_customer->editAddress($customer_id, $post_info['address_id'], $post_info);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Delete
	 *
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('customer/customer');

		$json = [];

		if (isset($this->request->get['address_id'])) {
			$address_id = (int)$this->request->get['address_id'];
		} else {
			$address_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'customer/customer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Customer
		$this->load->model('customer/customer');

		$address_info = $this->model_customer_customer->getAddress($address_id);

		if (!$address_info) {
			$json['error'] = $this->language->get('error_address');
		}

		if (!$json) {
			$this->model_customer_customer->deleteAddresses($address_info['customer_id'], $address_id);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Address
	 *
	 * @return void
	 */
	public function address(): void {
		$this->load->language('customer/customer');

		$json = [];

		if (isset($this->request->get['address_id'])) {
			$address_id = (int)$this->request->get['address_id'];
		} else {
			$address_id = 0;
		}

		// Customer
		$this->load->model('customer/customer');

		$address_info = $this->model_customer_customer->getAddress($address_id);

		if (!$address_info) {
			$json['error'] = $this->language->get('error_address');
		}

		if (!$json) {
			$json = $address_info;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
