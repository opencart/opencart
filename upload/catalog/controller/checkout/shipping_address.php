<?php
namespace Opencart\Catalog\Controller\Checkout;
/**
 * Class ShippingAddress
 *
 * @package Opencart\Catalog\Controller\Checkout
 */
class ShippingAddress extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('checkout/shipping_address');

		$data['error_upload_size'] = sprintf($this->language->get('error_upload_size'), $this->config->get('config_file_max_size'));
		$data['config_file_max_size'] = ((int)$this->config->get('config_file_max_size') * 1024 * 1024);
		$data['payment_address_required'] = $this->config->get('config_checkout_payment_address');

		$this->session->data['upload_token'] = oc_token(32);

		$data['upload'] = $this->url->link('tool/upload', 'language=' . $this->config->get('config_language') . '&upload_token=' . $this->session->data['upload_token']);

		$this->load->model('account/address');

		$data['addresses'] = $this->model_account_address->getAddresses($this->customer->getId());

		if (isset($this->session->data['shipping_address']['address_id'])) {
			$data['address_id'] = $this->session->data['shipping_address']['address_id'];
		} else {
			$data['address_id'] = 0;
		}

		if (isset($this->session->data['shipping_address'])) {
			$data['postcode'] = $this->session->data['shipping_address']['postcode'];
			$data['country_id'] = $this->session->data['shipping_address']['country_id'];
			$data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
		} else {
			$data['postcode'] = '';
			$data['country_id'] = (int)$this->config->get('config_country_id');
			$data['zone_id'] = '';
		}

		// Country
		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		// Zone
		$this->load->model('localisation/zone');

		$data['zones'] = $this->model_localisation_zone->getZonesByCountryId($data['country_id']);

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

		return $this->load->view('checkout/shipping_address', $data);
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('checkout/shipping_address');

		$json = [];

		// Validate cart has products and has stock.
		if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout')) || !$this->cart->hasMinimum()) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate if customer is logged in or customer session data is not set
		if (!$this->customer->isLogged() || !isset($this->session->data['customer'])) {
			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate if shipping not required
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			$keys = [
				'firstname',
				'lastname',
				'company',
				'address_1',
				'address_2',
				'city',
				'postcode',
				'country_id',
				'zone_id',
				'custom_field'
			];

			foreach ($keys as $key) {
				if (!isset($this->request->post[$key])) {
					$this->request->post[$key] = '';
				}
			}

			if (!oc_validate_length($this->request->post['firstname'], 1, 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}

			if (!oc_validate_length($this->request->post['lastname'], 1, 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}

			if (!oc_validate_length($this->request->post['address_1'], 3, 128)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}

			if (!oc_validate_length($this->request->post['city'], 2, 128)) {
				$json['error']['city'] = $this->language->get('error_city');
			}

			// Country
			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry((int)$this->request->post['country_id']);

			if ($country_info && $country_info['postcode_required'] && !oc_validate_length($this->request->post['postcode'], 2, 10)) {
				$json['error']['postcode'] = $this->language->get('error_postcode');
			}

			if (!$country_info || $this->request->post['country_id'] == '') {
				$json['error']['country'] = $this->language->get('error_country');
			}

			if ($this->request->post['zone_id'] == '') {
				$json['error']['zone'] = $this->language->get('error_zone');
			}

			// Custom field validation
			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields($this->customer->getGroupId());

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address') {
					if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
						$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !oc_validate_regex($this->request->post['custom_field'][$custom_field['custom_field_id']], $custom_field['validation'])) {
						$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
					}
				}
			}
		}

		if (!$json) {
			// If no default address add it
			$address_id = $this->customer->getAddressId();

			if (!$address_id) {
				$this->request->post['default'] = 1;
			}

			$this->load->model('account/address');

			$json['address_id'] = $this->model_account_address->addAddress($this->customer->getId(), $this->request->post);

			$json['addresses'] = $this->model_account_address->getAddresses($this->customer->getId());

			$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getId(), $json['address_id']);

			$json['success'] = $this->language->get('text_success');

			// Clear payment and shipping methods
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
		$this->load->language('checkout/shipping_address');

		$json = [];

		if (isset($this->request->get['address_id'])) {
			$address_id = (int)$this->request->get['address_id'];
		} else {
			$address_id = 0;
		}

		// Validate cart has products and has stock.
		if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout')) || !$this->cart->hasMinimum()) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate if customer is logged in or customer session data is not set
		if (!$this->customer->isLogged() || !isset($this->session->data['customer'])) {
			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate if shipping is not required
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			$this->load->model('account/address');

			$address_info = $this->model_account_address->getAddress($this->customer->getId(), $address_id);

			if (!$address_info) {
				$json['error'] = $this->language->get('error_address');

				unset($this->session->data['shipping_address']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			}
		}

		if (!$json) {
			$this->session->data['shipping_address'] = $address_info;

			$json['success'] = $this->language->get('text_success');

			// Clear payment and shipping methods
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
