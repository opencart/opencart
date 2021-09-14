<?php
namespace Opencart\Catalog\Controller\Checkout;
class ShippingAddress extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('checkout/checkout');

		$data['error_upload_size'] = sprintf($this->language->get('error_upload_size'), $this->config->get('config_file_max_size'));

		$data['config_checkout_address'] = $this->config->get('config_checkout_address');
		$data['config_file_max_size'] = $this->config->get('config_file_max_size');
		$data['config_checkout_address'] = $this->config->get('config_checkout_address');

		$data['logged'] = $this->customer->isLogged();

		if (isset($this->session->data['shipping_address']['address_id'])) {
			$data['address_id'] = (int)$this->session->data['shipping_address']['address_id'];
		} else {
			$data['address_id'] = $this->customer->getAddressId();
		}

		$data['addresses'] = [];

		$this->load->model('account/address');

		$results = $this->model_account_address->getAddresses();

		foreach ($results as $result) {
			$data['addresses'][] = [
				'text'  => $result['firstname'] . ' ' . $result['lastname'] . ', ' . $result['address_1'] . ', ' . $result['city'] . ', ' . $result['zone'] . ', ' . $result['country'],
				'value' => $result['address_id']
			];
		}

		if (isset($this->session->data['shipping_address'])) {
			$data['firstname'] = $this->session->data['shipping_address']['firstname'];
			$data['lastname'] = $this->session->data['shipping_address']['lastname'];
			$data['company'] = $this->session->data['shipping_address']['company'];
			$data['address_1'] = $this->session->data['shipping_address']['address_1'];
			$data['address_2'] = $this->session->data['shipping_address']['address_2'];
			$data['postcode'] = $this->session->data['shipping_address']['postcode'];
			$data['city'] = $this->session->data['shipping_address']['city'];
			$data['country_id'] = (int)$this->session->data['shipping_address']['country_id'];
			$data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
			$data['custom_field'] = $this->session->data['shipping_address']['custom_field'];
		} else {
			$data['firstname'] = '';
			$data['lastname'] = '';
			$data['company'] = '';
			$data['address_1'] = '';
			$data['address_2'] = '';
			$data['postcode'] = '';
			$data['city'] = '';
			$data['country_id'] = $this->config->get('config_country_id');
			$data['zone_id'] = '';
			$data['custom_field'] = [];
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		// Custom Fields
		$data['custom_fields'] = [];

		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields((int)$this->config->get('config_customer_group_id'));

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'address') {
				$data['custom_fields'][] = $custom_field;
			}
		}

		return $this->load->view('checkout/shipping_address', $data);
	}

	public function save(): void {
		$this->load->language('checkout/checkout');

		$json = [];

		// Validate if shipping is required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping()) {
			$json['redirect'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);

				break;
			}
		}

		if (!$json) {
			$keys = [
				'account',
				'firstname',
				'lastname',
				'company',
				'address_1',
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

			if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}

			if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}

			if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}

			if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
				$json['error']['city'] = $this->language->get('error_city');
			}

			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

			if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2 || utf8_strlen($this->request->post['postcode']) > 10)) {
				$json['error']['postcode'] = $this->language->get('error_postcode');
			}

			if ($this->request->post['country_id'] == '') {
				$json['error']['country'] = $this->language->get('error_country');
			}

			if ($this->request->post['zone_id'] == '') {
				$json['error']['zone'] = $this->language->get('error_zone');
			}

			// Custom field validation
			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields((int)$this->config->get('config_customer_group_id'));

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address') {
					if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
						$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !preg_match(html_entity_decode($custom_field['validation'], ENT_QUOTES, 'UTF-8'), $this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
						$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
					}
				}
			}
		}

		if (!$json) {
			if ($this->customer->isLogged()) {
				$this->load->model('account/address');

				$address_id = $this->model_account_address->addAddress($this->customer->getId(), $this->request->post);

				// If no default address ID set we use the last address
				if (!$this->customer->getAddressId()) {
					$this->load->model('account/customer');

					$this->model_account_customer->editAddressId($this->customer->getId(), $address_id);
				}
			}

			$this->session->data['shipping_address'] = [
				'address_id'   => $address_id,
				'firstname'    => $this->request->post['firstname'],
				'lastname'     => $this->request->post['lastname'],
				'company'      => $this->request->post['company'],
				'address_1'    => $this->request->post['address_1'],
				'address_2'    => $this->request->post['address_2'],
				'city'         => $this->request->post['city'],
				'postcode'     => $this->request->post['postcode'],
				'country_id'   => $this->request->post['country_id'],
				'zone_id'      => $this->request->post['zone_id'],
				'custom_field' => isset($this->request->post['custom_field']) ? $this->request->post['custom_field'] : []
			];

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function address() {
		$this->load->language('checkout/checkout');

		$json = [];

		if (isset($this->request->get['address_id'])) {
			$address_id = (int)$this->request->get['address_id'];
		} else {
			$address_id = 0;
		}

		if (!$this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);

				break;
			}
		}

		if (!$json) {
			$this->load->model('account/address');

			$address_info = $this->model_account_address->getAddress($address_id);

			if (!$address_info) {
				$json['error'] = $this->language->get('error_address');
			}
		}

		if (!$json) {
			$this->session->data['shipping_address'] = $address_info;

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);

			$json['success'] = 'Success: Your address has been successfully created!';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}