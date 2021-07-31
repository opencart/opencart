<?php
namespace Opencart\Catalog\Controller\Checkout;
class Register extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('checkout/checkout');

		$data['entry_newsletter'] = sprintf($this->language->get('entry_newsletter'), $this->config->get('config_name'));

		$data['error_upload_size'] = sprintf($this->language->get('error_upload_size'), $this->config->get('config_file_max_size'));

		$data['config_checkout_guest'] = ($this->config->get('config_checkout_guest') && !$this->config->get('config_customer_price') && !$this->cart->hasDownload());
		$data['config_checkout_address'] = $this->config->get('config_checkout_address');
		$data['config_file_max_size'] = $this->config->get('config_file_max_size');

		$data['shipping_required'] = $this->cart->hasShipping();

		$data['customer_groups'] = [];

		if (is_array($this->config->get('config_customer_group_display'))) {
			$this->load->model('account/customer_group');

			$customer_groups = $this->model_account_customer_group->getCustomerGroups();

			foreach ($customer_groups  as $customer_group) {
				if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
					$data['customer_groups'][] = $customer_group;
				}
			}
		}

		if (isset($this->session->data['account'])) {
			$data['account'] = $this->session->data['account'];
		} else {
			$data['account'] = 1;
		}

		if (isset($this->session->data['customer']['customer_group_id'])) {
			$data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
		} else {
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}

		if (isset($this->session->data['customer']['firstname'])) {
			$data['firstname'] = $this->session->data['customer']['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->session->data['customer']['lastname'])) {
			$data['lastname'] = $this->session->data['customer']['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (isset($this->session->data['customer']['email'])) {
			$data['email'] = $this->session->data['customer']['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->session->data['customer']['telephone'])) {
			$data['telephone'] = $this->session->data['customer']['telephone'];
		} else {
			$data['telephone'] = '';
		}

		// Custom Fields
		$this->load->model('account/custom_field');

		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields();

		if (isset($this->session->data['customer']['custom_field'])) {
			$data['customer_custom_field'] = $this->session->data['customer']['custom_field'];
		} else {
			$data['customer_custom_field'] = [];
		}

		// Payment Address
		if (isset($this->session->data['payment_address']['company'])) {
			$data['payment_company'] = $this->session->data['payment_address']['company'];
		} else {
			$data['payment_company'] = '';
		}

		if (isset($this->session->data['payment_address']['address_1'])) {
			$data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
		} else {
			$data['payment_address_1'] = '';
		}

		if (isset($this->session->data['payment_address']['address_2'])) {
			$data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
		} else {
			$data['payment_address_2'] = '';
		}

		if (isset($this->session->data['payment_address']['postcode'])) {
			$data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
		} else {
			$data['payment_postcode'] = '';
		}

		if (isset($this->session->data['payment_address']['city'])) {
			$data['payment_city'] = $this->session->data['payment_address']['city'];
		} else {
			$data['payment_city'] = '';
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		if (isset($this->session->data['payment_address']['country_id'])) {
			$data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
		} else {
			$data['payment_country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->session->data['payment_address']['zone_id'])) {
			$data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
		} else {
			$data['payment_zone_id'] = '';
		}

		if (isset($this->session->data['payment_address']['custom_field'])) {
			$data['payment_custom_field'] = $this->session->data['payment_address']['custom_field'];
		} else {
			$data['payment_custom_field'] = [];
		}

		// Shipping Address
		if (isset($this->session->data['shipping_address']['firstname'])) {
			$data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
		} else {
			$data['shipping_firstname'] = '';
		}

		if (isset($this->session->data['shipping_address']['lastname'])) {
			$data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
		} else {
			$data['shipping_lastname'] = '';
		}

		if (isset($this->session->data['shipping_address']['company'])) {
			$data['shipping_company'] = $this->session->data['shipping_address']['company'];
		} else {
			$data['shipping_company'] = '';
		}

		if (isset($this->session->data['shipping_address']['address_1'])) {
			$data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
		} else {
			$data['shipping_address_1'] = '';
		}

		if (isset($this->session->data['shipping_address']['address_2'])) {
			$data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
		} else {
			$data['shipping_address_2'] = '';
		}

		if (isset($this->session->data['shipping_address']['postcode'])) {
			$data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
		} else {
			$data['shipping_postcode'] = '';
		}

		if (isset($this->session->data['shipping_address']['city'])) {
			$data['shipping_city'] = $this->session->data['shipping_address']['city'];
		} else {
			$data['shipping_city'] = '';
		}

		if (isset($this->session->data['shipping_address']['country_id'])) {
			$data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
		} else {
			$data['shipping_country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->session->data['shipping_address']['zone_id'])) {
			$data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
		} else {
			$data['shipping_zone_id'] = '';
		}

		// Captcha
		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

		if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/'  . $extension_info['extension'] . '/captcha/' . $extension_info['code']);
		} else {
			$data['captcha'] = '';
		}

		$this->load->model('catalog/information');

		$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

		if ($information_info) {
			$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information|info', 'language=' . $this->config->get('config_language') . '&information_id=' . $this->config->get('config_account_id')), $information_info['title']);
		} else {
			$data['text_agree'] = '';
		}

		return $this->load->view('checkout/register', $data);
	}

	public function save(): void {
		$this->load->language('checkout/checkout');

		$json = [];

		// Validate if customer is already logged out.
		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] =  $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
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
				'customer_group_id',
				'firstname',
				'lastname',
				'email',
				'telephone',
				'payment_company',
				'payment_address_1',
				'payment_address_2',
				'payment_city',
				'payment_postcode',
				'payment_country_id',
				'payment_zone_id',
				'shipping_address',
				'shipping_firstname',
				'shipping_lastname',
				'shipping_company',
				'shipping_address_1',
				'shipping_address_2',
				'shipping_city',
				'shipping_postcode',
				'shipping_country_id',
				'shipping_zone_id',
				'password',
				'confirm',
				'agree'
			];

			foreach ($keys as $key) {
				if (!isset($this->request->post[$key])) {
					$this->request->post[$key] = '';
				}
			}

			// Customer
			if (in_array((int)$this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
				$customer_group_id = (int)$this->request->post['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}

			// Use _custromer to separate error ids
			if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
				$json['error']['customer_firstname'] = $this->language->get('error_firstname');
			}

			if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
				$json['error']['customer_lastname'] = $this->language->get('error_lastname');
			}

			if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
				$json['error']['customer_email'] = $this->language->get('error_email');
			}

			$this->load->model('account/customer');

			if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
				$json['error']['warning'] = $this->language->get('error_exists');
			}

			if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
				$json['error']['customer_telephone'] = $this->language->get('error_telephone');
			}

			// Custom field validation
			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'account') {
					if ($custom_field['required'] && empty($this->request->post['customer_custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
						$json['error']['customer_custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !preg_match(html_entity_decode($custom_field['validation'], ENT_QUOTES, 'UTF-8'), $this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
						$json['error']['customer_custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
					}
				}
			}


			//print_r($this->request->post);

			if ($this->request->post['account']) {


				if ((utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
					$json['error']['customer_password'] = $this->language->get('error_password');
				}

				if ($this->request->post['confirm'] != $this->request->post['password']) {
					$json['error']['customer_confirm'] = $this->language->get('error_confirm');
				}

			}

			// Payment Address
			if ($this->config->get('config_checkout_address')) {
				if ((utf8_strlen(trim($this->request->post['payment_address_1'])) < 3) || (utf8_strlen(trim($this->request->post['payment_address_1'])) > 128)) {
					$json['error']['payment_address_1'] = $this->language->get('error_address_1');
				}

				if ((utf8_strlen($this->request->post['payment_city']) < 2) || (utf8_strlen($this->request->post['payment_city']) > 32)) {
					$json['error']['payment_city'] = $this->language->get('error_city');
				}

				$this->load->model('localisation/country');

				$country_info = $this->model_localisation_country->getCountry((int)$this->request->post['payment_country_id']);

				if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['payment_postcode'])) < 2 || utf8_strlen(trim($this->request->post['payment_postcode'])) > 10)) {
					$json['error']['payment_postcode'] = $this->language->get('error_postcode');
				}

				if ($this->request->post['payment_country_id'] == '') {
					$json['error']['payment_country'] = $this->language->get('error_country');
				}

				if (!isset($this->request->post['payment_zone_id']) || $this->request->post['payment_zone_id'] == '') {
					$json['error']['payment_zone'] = $this->language->get('error_zone');
				}

				// Custom field validation
				foreach ($custom_fields as $custom_field) {
					if ($custom_field['location'] == 'address') {
						if ($custom_field['required'] && empty($this->request->post['payment_custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
							$json['error']['payment_custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
						} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !preg_match(html_entity_decode($custom_field['validation'], ENT_QUOTES, 'UTF-8'), $this->request->post['payment_custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
							$json['error']['payment_custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
						}
					}
				}
			}

			// Shipping Address
			if ($this->cart->hasShipping() && !$this->request->post['shipping_address']) {
				if ((utf8_strlen(trim($this->request->post['shipping_firstname'])) < 1) || (utf8_strlen(trim($this->request->post['shipping_firstname'])) > 32)) {
					$json['error']['shipping_firstname'] = $this->language->get('error_firstname');
				}

				if ((utf8_strlen(trim($this->request->post['shipping_lastname'])) < 1) || (utf8_strlen(trim($this->request->post['shipping_lastname'])) > 32)) {
					$json['error']['shipping_lastname'] = $this->language->get('error_lastname');
				}

				if ((utf8_strlen(trim($this->request->post['shipping_address_1'])) < 3) || (utf8_strlen(trim($this->request->post['shipping_address_1'])) > 128)) {
					$json['error']['shipping_address_1'] = $this->language->get('error_address_1');
				}

				if ((utf8_strlen($this->request->post['shipping_city']) < 2) || (utf8_strlen($this->request->post['shipping_city']) > 32)) {
					$json['error']['shipping_city'] = $this->language->get('error_city');
				}

				$this->load->model('localisation/country');

				$country_info = $this->model_localisation_country->getCountry((int)$this->request->post['shipping_country_id']);

				if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['shipping_postcode'])) < 2 || utf8_strlen(trim($this->request->post['shipping_postcode'])) > 10)) {
					$json['error']['shipping_postcode'] = $this->language->get('error_postcode');
				}

				if ($this->request->post['shipping_country_id'] == '') {
					$json['error']['shipping_country'] = $this->language->get('error_country');
				}

				if (!isset($this->request->post['shipping_zone_id']) || $this->request->post['shipping_zone_id'] == '') {
					$json['error']['shipping_zone'] = $this->language->get('error_zone');
				}

				// Custom field validation
				foreach ($custom_fields as $custom_field) {
					if ($custom_field['location'] == 'address') {
						if ($custom_field['required'] && empty($this->request->post['shipping_custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
							$json['error']['shipping_custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
						} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !preg_match(html_entity_decode($custom_field['validation'], ENT_QUOTES, 'UTF-8'), $this->request->post['shipping_custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
							$json['error']['shipping_custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
						}
					}
				}
			}

			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

			if ($information_info && !$this->request->post['agree']) {
				$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}

			// Captcha
			$this->load->model('setting/extension');

			$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

			if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('extension/'  . $extension_info['extension'] . '/captcha/' . $extension_info['code'] . '|validate');

				if ($captcha) {
					$json['error']['captcha'] = $captcha;
				}
			}
		}

		if (!$json) {
			// For guest account
			$customer_id = 0;

			$this->session->data['account'] = $this->request->post['account'];

			// Create account
			if ($this->request->post['account']) {
				$customer_id = $this->model_account_customer->addCustomer($this->request->post);

				// Add payment address to customer account
				if ($this->config->get('config_checkout_address')) {
					$payment_address_data = [
						'firstname'    => $this->request->post['payment_firstname'],
						'lastname'     => $this->request->post['payment_lastname'],
						'company'      => $this->request->post['payment_company'],
						'address_1'    => $this->request->post['payment_address_1'],
						'address_2'    => $this->request->post['payment_address_2'],
						'city'         => $this->request->post['payment_city'],
						'postcode'     => $this->request->post['payment_postcode'],
						'country_id'   => $this->request->post['payment_country_id'],
						'zone_id'      => $this->request->post['payment_zone_id'],
						'custom_field' => $this->request->post['payment_custom_field']
					];

					$this->load->model('account/address');

					$address_id = $this->model_account_address->addAddress($customer_id, $payment_address_data);

					// Set the address as default
					$this->model_account_customer->editAddressId($customer_id, $address_id);
				}

				// Add shipping address to customer account
				if ($this->cart->hasShipping() && !$this->request->post['shipping_address']) {
					$shipping_address_data = [
						'firstname'    => $this->request->post['shipping_firstname'],
						'lastname'     => $this->request->post['shipping_lastname'],
						'company'      => $this->request->post['shipping_company'],
						'address_1'    => $this->request->post['shipping_address_1'],
						'address_2'    => $this->request->post['shipping_address_2'],
						'city'         => $this->request->post['shipping_city'],
						'postcode'     => $this->request->post['shipping_postcode'],
						'country_id'   => $this->request->post['shipping_country_id'],
						'zone_id'      => $this->request->post['shipping_zone_id'],
						'custom_field' => $this->request->post['shipping_custom_field']
					];

					$address_id = $this->model_account_address->addAddress($customer_id, $shipping_address_data);

					// Set the shipping address as default if no payment checkout is being used
					if (!$this->config->get('config_checkout_address')) {
						$this->model_account_customer->editAddressId($customer_id, $address_id);
					}
				}
			}

			// Add customer details into session
			$this->session->data['customer'] = [
				'customer_id'       => $customer_id,
				'customer_group_id' => $customer_group_id,
				'firstname'         => $this->request->post['firstname'],
				'lastname'          => $this->request->post['lastname'],
				'email'             => $this->request->post['email'],
				'telephone'         => $this->request->post['telephone'],
				'custom_field'      => $this->request->post['custom_field']
			];

			// Add payment address into session
			if ($this->config->get('config_checkout_address')) {
				$this->session->data['payment_address'] = [
					'firstname'    => $this->request->post['payment_firstname'],
					'lastname'     => $this->request->post['payment_lastname'],
					'company'      => $this->request->post['payment_company'],
					'address_1'    => $this->request->post['payment_address_1'],
					'address_2'    => $this->request->post['payment_address_2'],
					'city'         => $this->request->post['payment_city'],
					'postcode'     => $this->request->post['payment_postcode'],
					'country_id'   => $this->request->post['payment_country_id'],
					'zone_id'      => $this->request->post['payment_zone_id'],
					'custom_field' => $this->request->post['payment_custom_field']
				];

				//  If payment and shipping address are the same
				if ($this->cart->hasShipping() && $this->request->post['shipping_address']) {
					$this->session->data['shipping_address'] = $this->session->data['payment_address'];
				}
			}

			// Add shipping address into session
			if ($this->cart->hasShipping() && !$this->request->post['shipping_address']) {
				$this->session->data['shipping_address'] = [
					'firstname'    => $this->request->post['shipping_firstname'],
					'lastname'     => $this->request->post['shipping_lastname'],
					'company'      => $this->request->post['shipping_company'],
					'address_1'    => $this->request->post['shipping_address_1'],
					'address_2'    => $this->request->post['shipping_address_2'],
					'city'         => $this->request->post['shipping_city'],
					'postcode'     => $this->request->post['shipping_postcode'],
					'country_id'   => $this->request->post['shipping_country_id'],
					'zone_id'      => $this->request->post['shipping_zone_id'],
					'custom_field' => $this->request->post['shipping_custom_field']
				];
			}

			// Check if current customer group requires approval
			$this->load->model('account/customer_group');

			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

			if ($customer_group_info && !$customer_group_info['approval']) {
				// If everything good login
				$this->customer->login($this->request->post['email'], $this->request->post['password']);
			}

			// Redirect to success page
			$json['success'] = '';

			// Clear any previous login attempts for unregistered accounts.
			$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
