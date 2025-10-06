<?php
namespace Opencart\Catalog\Controller\Checkout;
/**
 * Class PaymentAddress
 *
 * @package Opencart\Catalog\Controller\Checkout
 */
class PaymentAddress extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('checkout/payment_address');

		$data['error_upload_size'] = sprintf($this->language->get('error_upload_size'), $this->config->get('config_file_max_size'));
		$data['config_file_max_size'] = ((int)$this->config->get('config_file_max_size') * 1024 * 1024);

		$this->session->data['upload_token'] = oc_token(32);

		$data['upload'] = $this->url->link('tool/upload', 'language=' . $this->config->get('config_language') . '&upload_token=' . $this->session->data['upload_token']);

		// Payment Address
		$this->load->model('account/address');

		$data['addresses'] = $this->model_account_address->getAddresses($this->customer->getId());

		if (isset($this->session->data['payment_address']['address_id'])) {
			$data['address_id'] = $this->session->data['payment_address']['address_id'];
		} else {
			$data['address_id'] = 0;
		}

		// Countries
		$data['country_id'] = (int)$this->config->get('config_country_id');

		// Custom Fields
		$data['custom_fields'] = [];

		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields($this->customer->getGroupId());

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'address') {
				$data['custom_fields'][] = $custom_field;
			}
		}

		$data['language'] = $this->config->get('config_language');

		return $this->load->view('checkout/payment_address', $data);
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('checkout/payment_address');

		$json = [];

		$required = [
			'firstname'    => '',
			'lastname'     => '',
			'company'      => '',
			'address_1'    => '',
			'address_2'    => '',
			'city'         => '',
			'postcode'     => '',
			'country_id'   => 0,
			'zone_id'      => 0,
			'custom_field' => []
		];

		$post_info = $this->request->post + $required;

		// Validate cart has products and has stock.
		if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout')) || !$this->cart->hasMinimum()) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate if customer is logged in or customer session data is not set
		if (!$this->customer->isLogged() || !isset($this->session->data['customer'])) {
			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate if payment address is set if required, in settings
		if (!$this->config->get('config_checkout_payment_address')) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			if (!oc_validate_length($post_info['firstname'], 1, 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}

			if (!oc_validate_length($post_info['lastname'], 1, 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}

			if (!oc_validate_length($post_info['address_1'], 3, 128)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}

			if (!oc_validate_length($post_info['city'], 2, 128)) {
				$json['error']['city'] = $this->language->get('error_city');
			}

			// Country
			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry((int)$post_info['country_id']);

			if ($country_info && $country_info['postcode_required'] && !oc_validate_length($post_info['postcode'], 2, 10)) {
				$json['error']['postcode'] = $this->language->get('error_postcode');
			}

			if (!$country_info) {
				$json['error']['country'] = $this->language->get('error_country');
			}

			// Zone
			$this->load->model('localisation/zone');

			// Total Zones
			$zone_total = $this->model_localisation_zone->getTotalZonesByCountryId((int)$post_info['country_id']);

			if ($zone_total && !$post_info['zone_id']) {
				$json['error']['zone'] = $this->language->get('error_zone');
			}

			// Custom fields validation
			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields($this->customer->getGroupId());

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address') {
					if ($custom_field['required'] && empty($post_info['custom_field'][$custom_field['custom_field_id']])) {
						$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !oc_validate_regex($post_info['custom_field'][$custom_field['custom_field_id']], $custom_field['validation'])) {
						$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
					}
				}
			}
		}

		if (!$json) {
			// If no default address has been found, add it
			$address_id = $this->customer->getAddressId();

			if (!$address_id) {
				$post_info['default'] = 1;
			}

			// Address
			$this->load->model('account/address');

			$json['address_id'] = $this->model_account_address->addAddress($this->customer->getId(), $post_info);

			// Addresses
			$json['addresses'] = $this->model_account_address->getAddresses($this->customer->getId());

			$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getId(), $json['address_id']);

			$json['success'] = $this->language->get('text_success');

			// Clear payment and shipping methods
			unset($this->session->data['order_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
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
		$this->load->language('checkout/payment_address');

		$json = [];

		if (isset($this->request->get['address_id'])) {
			$address_id = (int)$this->request->get['address_id'];
		} else {
			$address_id = 0;
		}

		if (!isset($this->session->data['customer'])) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate cart has products and has stock.
		if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout')) || !$this->cart->hasMinimum()) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate if customer is logged in or customer session data is not set
		if (!$this->customer->isLogged() || !isset($this->session->data['customer'])) {
			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate if payment address is set if required, in settings
		if (!$this->config->get('config_checkout_payment_address')) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			// Payment Address
			$this->load->model('account/address');

			$address_info = $this->model_account_address->getAddress($this->customer->getId(), $address_id);

			if (!$address_info) {
				$json['error'] = $this->language->get('error_address');
			}
		}

		if (!$json) {
			$this->session->data['payment_address'] = $address_info;

			$json['success'] = $this->language->get('text_success');

			// Clear payment and shipping methods
			unset($this->session->data['order_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
