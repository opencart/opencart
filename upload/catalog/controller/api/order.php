<?php
namespace Opencart\Catalog\Controller\Api;
class Order extends \Opencart\System\Engine\Controller {
	public function load(): void {
		$this->load->language('api/order');

		$json = [];

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if (!$order_info) {
			$json['error'] = $this->language->get('error_not_found');
		}

		if (!$json) {
			$this->session->data['language'] = [];

			// Customer Details
			$this->session->data['customer'] = [
				'customer_id'       => $order_info['customer_id'],
				'customer_group_id' => $order_info['customer_group_id'],
				'firstname'         => $order_info['firstname'],
				'lastname'          => $order_info['lastname'],
				'email'             => $order_info['email'],
				'telephone'         => $order_info['telephone'],
				'custom_field'      => $order_info['custom_field']
			];

			// Payment Details
			$this->session->data['payment_address'] = [
				'firstname'      => $order_info['payment_firstname'],
				'lastname'       => $order_info['payment_lastname'],
				'company'        => $order_info['payment_company'],
				'address_1'      => $order_info['payment_address_1'],
				'address_2'      => $order_info['payment_address_2'],
				'postcode'       => $order_info['payment_postcode'],
				'city'           => $order_info['payment_city'],
				'zone_id'        => $order_info['payment_zone_id'],
				'zone'           => $order_info['zone'],
				'zone_code'      => $order_info['zone_code'],
				'country_id'     => $order_info['payment_country_id'],
				'country'        => $order_info['country'],
				'iso_code_2'     => $order_info['iso_code_2'],
				'iso_code_3'     => $order_info['iso_code_3'],
				'address_format' => $order_info['address_format'],
				'custom_field'   => $order_info['payment_custom_field']
			];

			$this->session->data['shipping_address'] = [
				'firstname'      => $order_info['shipping_firstname'],
				'lastname'       => $order_info['shipping_lastname'],
				'company'        => $order_info['shipping_company'],
				'address_1'      => $order_info['shipping_address_1'],
				'address_2'      => $order_info['shipping_address_2'],
				'postcode'       => $order_info['shipping_postcode'],
				'city'           => $order_info['shipping_city'],
				'zone_id'        => $order_info['shipping_zone_id'],
				'zone'           => $order_info['zone'],
				'zone_code'      => $order_info['zone_code'],
				'country_id'     => $order_info['shipping_country_id'],
				'country'        => $order_info['country'],
				'iso_code_2'     => $order_info['iso_code_2'],
				'iso_code_3'     => $order_info['iso_code_3'],
				'address_format' => $order_info['address_format'],
				'custom_field'   => $order_info['shipping_custom_field']
			];

			$this->cart->clear();

			$products = $this->model_checkout_order->getProducts($order_id);

			foreach ($products as $product) {
				$options = $this->model_checkout_order->getOptions($order_id, $product['order_product_id']);

				foreach ($options as $option) {
					if (isset($option['product_option_id'])) {
						$option[$option['key']] = $option['value'];
					} else {
						$option = [];
					}
				}

				$this->cart->add($product['product_id'], $product['quantity'], $option);
			}

			$vouchers = $this->model_checkout_order->getVouchers($order_id);

			foreach ($vouchers as $voucher) {

			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/*
	 * Loads order info
	 * */
	public function info(): void {
		$this->load->language('api/order');

		$json = [];

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if (!$order_info) {
			$json['error'] = $this->language->get('error_not_found');
		}

		if (!$json) {
			$this->load->model('checkout/order');

			$json['order'] = $order_info;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function customer(): void {
		$this->load->language('api/customer');

		$json = [];

		$keys = [
			'customer_id',
			'customer_group_id',
			'firstname',
			'lastname',
			'email',
			'telephone'
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
				$json['error'] = $this->language->get('error_customer_not_found');
			}
		}

		if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
			$json['error']['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
			$json['error']['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$json['error']['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$json['error']['telephone'] = $this->language->get('error_telephone');
		}

		// Customer Group
		if (isset($this->request->post['customer_group_id']) && in_array($this->request->post['customer_group_id'], (array)$this->config->get('config_customer_group_display'))) {
			$customer_group_id = (int)$this->request->post['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		// Custom field validation
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'account') {
				if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
					$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !preg_match(html_entity_decode($custom_field['validation'], ENT_QUOTES, 'UTF-8'), $this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
					$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
				}
			}
		}

		if (!$json) {
			$this->session->data['customer'] = [
				'customer_id'       => $customer_info['customer_id'],
				'customer_group_id' => $customer_info['customer_group_id'],
				'firstname'         => $customer_info['firstname'],
				'lastname'          => $customer_info['lastname'],
				'email'             => $customer_info['email'],
				'telephone'         => $customer_info['telephone'],
				'custom_field'      => $customer_info['custom_field']
			];

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function payment_address(): void {
		$this->load->language('api/payment_address');

		$json = [];

		// Add keys for missing post vars
		$keys = [
			'firstname',
			'lastname',
			'company',
			'address_1',
			'address_2',
			'postcode',
			'city',
			'zone_id',
			'country_id'
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

		if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 32)) {
			$json['error']['city'] = $this->language->get('error_city');
		}

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry((int)$this->request->post['country_id']);

		if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2 || utf8_strlen($this->request->post['postcode']) > 10)) {
			$json['error']['postcode'] = $this->language->get('error_postcode');
		}

		if (!filter_var($this->request->post['country_id'], FILTER_VALIDATE_INT)) {
			$json['error']['country'] = $this->language->get('error_country');
		}

		if (!isset($this->request->post['zone_id']) || filter_var($this->request->post['zone_id'], FILTER_VALIDATE_INT) === false) {
			$json['error']['zone'] = $this->language->get('error_zone');
		}

		// Custom field validation
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields((int)$this->config->get('config_customer_group_id'));

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'address') {
				if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
					$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !preg_match(html_entity_decode($custom_field['validation'], ENT_QUOTES, 'UTF-8'), $this->request->post['custom_field'][$custom_field['custom_field_id']])) {
					$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
				}
			}
		}

		print_r($this->config->get('config_customer_group_id'));
		print_r($custom_fields);

		if (!$json) {
			$country_info = $this->model_localisation_country->getCountry((int)$this->request->post['country_id']);

			if ($country_info) {
				$country = $country_info['name'];
				$iso_code_2 = $country_info['iso_code_2'];
				$iso_code_3 = $country_info['iso_code_3'];
				$address_format = $country_info['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$this->load->model('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);

			if ($zone_info) {
				$zone = $zone_info['name'];
				$zone_code = $zone_info['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$this->session->data['payment_address'] = [
				'firstname'      => $this->request->post['firstname'],
				'lastname'       => $this->request->post['lastname'],
				'company'        => $this->request->post['company'],
				'address_1'      => $this->request->post['address_1'],
				'address_2'      => $this->request->post['address_2'],
				'postcode'       => $this->request->post['postcode'],
				'city'           => $this->request->post['city'],
				'zone_id'        => $this->request->post['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => (int)$this->request->post['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => isset($this->request->post['custom_field']) ? $this->request->post['custom_field'] : []
			];

			$json['success'] = $this->language->get('text_success');

			// Delete old payment address, payment methods and method so not to cause any issues if there is an error
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function payment_method(): void {
		$this->load->language('api/payment_method');

		// Delete old payment method so not to cause any issues if there is an error
		unset($this->session->data['payment_method']);

		$json = [];

		// Payment Address
		if (!isset($this->session->data['payment_address'])) {
			$json['error'] = $this->language->get('error_address');
		}

		// Payment Method
		if (empty($this->session->data['payment_methods'])) {
			$json['error'] = $this->language->get('error_no_payment');
		}

		if (!isset($this->request->post['payment_method'])) {
			$json['error'] = $this->language->get('error_method');
		}

		if (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
			$json['error'] = $this->language->get('error_method');
		}

		if (!$json) {
			$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function shipping_address(): void {
		$this->load->language('api/shipping_address');

		$json = [];

		// Add keys for missing post vars
		$keys = [
			'firstname',
			'lastname',
			'company',
			'address_1',
			'address_2',
			'postcode',
			'city',
			'zone_id',
			'country_id'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		if ($this->cart->hasShipping()) {
			if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}

			if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}

			if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}

			if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 32)) {
				$json['error']['city'] = $this->language->get('error_city');
			}

			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry((int)$this->request->post['country_id']);

			if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2 || utf8_strlen($this->request->post['postcode']) > 10)) {
				$json['error']['postcode'] = $this->language->get('error_postcode');
			}

			if (!filter_var($this->request->post['country_id'], FILTER_VALIDATE_INT)) {
				$json['error']['country'] = $this->language->get('error_country');
			}

			if (!isset($this->request->post['zone_id']) || filter_var($this->request->post['zone_id'], FILTER_VALIDATE_INT) === false) {
				$json['error']['zone'] = $this->language->get('error_zone');
			}

			// Custom field validation
			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields((int)$this->config->get('config_customer_group_id'));

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address') {
					if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
						$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !preg_match(html_entity_decode($custom_field['validation'], ENT_QUOTES, 'UTF-8'), $this->request->post['custom_field'][$custom_field['custom_field_id']])) {
						$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
					}
				}
			}
		} else {
			$json['error'] = $this->language->get('error_product');
		}

		if (!$json) {
			if ($country_info) {
				$country = $country_info['name'];
				$iso_code_2 = $country_info['iso_code_2'];
				$iso_code_3 = $country_info['iso_code_3'];
				$address_format = $country_info['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$this->load->model('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);

			if ($zone_info) {
				$zone = $zone_info['name'];
				$zone_code = $zone_info['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			$this->session->data['shipping_address'] = [
				'firstname'      => $this->request->post['firstname'],
				'lastname'       => $this->request->post['lastname'],
				'company'        => $this->request->post['company'],
				'address_1'      => $this->request->post['address_1'],
				'address_2'      => $this->request->post['address_2'],
				'postcode'       => $this->request->post['postcode'],
				'city'           => $this->request->post['city'],
				'zone_id'        => $this->request->post['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => (int)$this->request->post['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => isset($this->request->post['custom_field']) ? $this->request->post['custom_field'] : []
			];

			// Delete old shipping address, shipping methods and method so not to cause any issues if there is an error
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['shipping_method']);

			$json['success'] = $this->language->get('text_success');

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function shipping_method(): void {
		$this->load->language('api/shipping_metho');

		// Delete old shipping method so not to cause any issues if there is an error
		unset($this->session->data['shipping_method']);

		$json = [];

		if ($this->cart->hasShipping()) {
			if (!isset($this->session->data['shipping_address'])) {
				$json['error'] = $this->language->get('error_address');
			}

			// Shipping Method
			if (empty($this->session->data['shipping_methods'])) {
				$json['error'] = $this->language->get('error_no_shipping');
			}

			if (isset($this->request->post['shipping_method'])) {
				$shipping = explode('.', $this->request->post['shipping_method']);

				if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
					$json['error'] = $this->language->get('error_method');
				}
			} else {
				$json['error'] = $this->language->get('error_method');
			}
		} else {
			$json['error'] = $this->language->get('error_product');
		}

		if (!$json) {
			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function save(): void {
		$this->load->language('api/order');

		$json = [];

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if (!$order_info) {
			$json['error'] = $this->language->get('error_not_found');
		}

		// Customer
		if (!isset($this->session->data['customer'])) {
			$json['error'] = $this->language->get('error_customer');
		}

		// Payment Address
		if (!isset($this->session->data['payment_address'])) {
			$json['error'] = $this->language->get('error_payment_address');
		}

		// Payment Method
		if (!$json && !empty($this->request->post['payment_method'])) {
			if (empty($this->session->data['payment_methods'])) {
				$json['error'] = $this->language->get('error_no_payment');
			} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
				$json['error'] = $this->language->get('error_payment_method');
			}

			if (!$json) {
				$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
			}
		}

		if (!isset($this->session->data['payment_method'])) {
			$json['error'] = $this->language->get('error_payment_method');
		}

		// Shipping
		if ($this->cart->hasShipping()) {
			// Shipping Address
			if (!isset($this->session->data['shipping_address'])) {
				$json['error'] = $this->language->get('error_shipping_address');
			}

			// Shipping Method
			if (!$json && !empty($this->request->post['shipping_method'])) {
				if (empty($this->session->data['shipping_methods'])) {
					$json['error'] = $this->language->get('error_no_shipping');
				} else {
					$shipping = explode('.', $this->request->post['shipping_method']);

					if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
						$json['error'] = $this->language->get('error_shipping_method');
					}
				}

				if (!$json) {
					$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
				}
			}

			// Shipping Method
			if (!isset($this->session->data['shipping_method'])) {
				$json['error'] = $this->language->get('error_shipping_method');
			}
		} else {
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		// Cart
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['error'] = $this->language->get('error_stock');
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
				$json['error'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);

				break;
			}
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');

			$order_data = [];

			// Store Details
			$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$order_data['store_id'] = $this->config->get('config_store_id');
			$order_data['store_name'] = $this->config->get('config_name');
			$order_data['store_url'] = $this->config->get('config_url');

			// Customer Details
			$order_data['customer_id'] = (int)$this->session->data['customer']['customer_id'];
			$order_data['customer_group_id'] = (int)$this->session->data['customer']['customer_group_id'];
			$order_data['firstname'] = $this->session->data['customer']['firstname'];
			$order_data['lastname'] = $this->session->data['customer']['lastname'];
			$order_data['email'] = $this->session->data['customer']['email'];
			$order_data['telephone'] = $this->session->data['customer']['telephone'];
			$order_data['custom_field'] = $this->session->data['customer']['custom_field'];

			// Payment Details
			$order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
			$order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
			$order_data['payment_company'] = $this->session->data['payment_address']['company'];
			$order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
			$order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
			$order_data['payment_city'] = $this->session->data['payment_address']['city'];
			$order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
			$order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
			$order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
			$order_data['payment_country'] = $this->session->data['payment_address']['country'];
			$order_data['payment_country_id'] = (int)$this->session->data['payment_address']['country_id'];
			$order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
			$order_data['payment_custom_field'] = (isset($this->session->data['payment_address']['custom_field']) ? $this->session->data['payment_address']['custom_field'] : []);

			if (isset($this->session->data['payment_method']['title'])) {
				$order_data['payment_method'] = $this->session->data['payment_method']['title'];
			} else {
				$order_data['payment_method'] = '';
			}

			if (isset($this->session->data['payment_method']['code'])) {
				$order_data['payment_code'] = $this->session->data['payment_method']['code'];
			} else {
				$order_data['payment_code'] = '';
			}

			// Shipping Details
			if ($this->cart->hasShipping()) {
				$order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
				$order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
				$order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
				$order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
				$order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
				$order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
				$order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
				$order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
				$order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
				$order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
				$order_data['shipping_country_id'] = (int)$this->session->data['shipping_address']['country_id'];
				$order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
				$order_data['shipping_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : []);

				if (isset($this->session->data['shipping_method']['title'])) {
					$order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
				} else {
					$order_data['shipping_method'] = '';
				}

				if (isset($this->session->data['shipping_method']['code'])) {
					$order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
				} else {
					$order_data['shipping_code'] = '';
				}
			} else {
				$order_data['shipping_firstname'] = '';
				$order_data['shipping_lastname'] = '';
				$order_data['shipping_company'] = '';
				$order_data['shipping_address_1'] = '';
				$order_data['shipping_address_2'] = '';
				$order_data['shipping_city'] = '';
				$order_data['shipping_postcode'] = '';
				$order_data['shipping_zone'] = '';
				$order_data['shipping_zone_id'] = '';
				$order_data['shipping_country'] = '';
				$order_data['shipping_country_id'] = '';
				$order_data['shipping_address_format'] = '';
				$order_data['shipping_custom_field'] = [];
				$order_data['shipping_method'] = '';
				$order_data['shipping_code'] = '';
			}

			// Products
			$order_data['products'] = [];

			foreach ($this->cart->getProducts() as $product) {
				$option_data = [];

				foreach ($product['option'] as $option) {
					$option_data[] = [
						'product_option_id' => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id' => $option['option_id'],
						'option_value_id' => $option['option_value_id'],
						'name' => $option['name'],
						'value' => $option['value'],
						'type' => $option['type']
					];
				}

				$order_data['products'][] = [
					'product_id' => $product['product_id'],
					'master_id' => $product['master_id'],
					'name' => $product['name'],
					'model' => $product['model'],
					'option' => $option_data,
					'download' => $product['download'],
					'quantity' => $product['quantity'],
					'subtract' => $product['subtract'],
					'price' => $product['price'],
					'total' => $product['total'],
					'tax' => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward' => $product['reward']
				];
			}

			// Gift Voucher
			$order_data['vouchers'] = [];

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$order_data['vouchers'][] = [
						'description' => $voucher['description'],
						'code' => token(10),
						'to_name' => $voucher['to_name'],
						'to_email' => $voucher['to_email'],
						'from_name' => $voucher['from_name'],
						'from_email' => $voucher['from_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message' => $voucher['message'],
						'amount' => $voucher['amount']
					];
				}
			}

			// Order Totals
			$this->load->model('setting/extension');

			$totals = [];
			$taxes = $this->cart->getTaxes();
			$total = 0;

			$sort_order = [];

			$results = $this->model_setting_extension->getExtensionsByType('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get('total_' . $result['code'] . '_status')) {
					$this->load->model('extension/' . $result['extension'] . '/total/' . $result['code']);

					// __call can not pass-by-reference so we get PHP to call it as an anonymous function.
					($this->{'model_extension_' . $result['extension'] . '_total_' . $result['code']}->getTotal)($totals, $taxes, $total);
				}
			}

			$sort_order = [];

			foreach ($totals as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $totals);

			$total_data = [
				'totals' => $totals,
				'taxes' => $taxes,
				'total' => $total
			];

			$order_data = array_merge($order_data, $total_data);

			if (isset($this->request->post['comment'])) {
				$order_data['comment'] = $this->request->post['comment'];
			} else {
				$order_data['comment'] = '';
			}

			$order_data['tracking'] = '';
			$order_data['affiliate_id'] = 0;
			$order_data['commission'] = 0;
			$order_data['marketing_id'] = 0;

			if (isset($this->request->post['affiliate_id']) && (int)$this->config->get('config_affiliate_status') == 1) {
				$subtotal = $this->cart->getSubTotal();

				// Affiliate
				$this->load->model('account/affiliate');

				$affiliate_info = $this->model_account_affiliate->getAffiliate((int)$this->request->post['affiliate_id']);

				if ($affiliate_info) {
					$order_data['affiliate_id'] = $affiliate_info['customer_id'];
					$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
				}
			}

			$order_data['language_id'] = $this->config->get('config_language_id');
			$order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
			$order_data['currency_code'] = $this->session->data['currency'];
			$order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
			$order_data['ip'] = $this->request->server['REMOTE_ADDR'];

			if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
				$order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
			} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
				$order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
			} else {
				$order_data['forwarded_ip'] = '';
			}

			if (isset($this->request->server['HTTP_USER_AGENT'])) {
				$order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
			} else {
				$order_data['user_agent'] = '';
			}

			if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
				$order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
			} else {
				$order_data['accept_language'] = '';
			}

			$this->load->model('checkout/order');

			$json['order_id'] = $this->model_checkout_order->addOrder($order_data);

			// Set the order history
			if (isset($this->request->post['order_status_id'])) {
				$order_status_id = (int)$this->request->post['order_status_id'];
			} else {
				$order_status_id = $this->config->get('config_order_status_id');
			}

			$this->model_checkout_order->addHistory($json['order_id'], $order_status_id);

			// clear cart since the order has already been successfully stored.
			$this->cart->clear();
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('api/order');

		$json = [];

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if (!$order_info) {
			$json['error'] = $this->language->get('error_not_found');
		}

		if (!$json) {
			$this->model_checkout_order->deleteOrder($order_id);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addHistory() {
		$this->load->language('api/order');

		$json = [];

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		// Add keys for missing post vars
		$keys = [
			'order_status_id',
			'notify',
			'override',
			'comment'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if (!$order_info) {
			$json['error'] = $this->language->get('error_not_found');
		}

		if (!$json) {
			$this->model_checkout_order->addHistory($order_id, (int)$this->request->post['order_status_id'], $this->request->post['comment'], $this->request->post['notify'], $this->request->post['override']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
