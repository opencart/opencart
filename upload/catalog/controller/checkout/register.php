<?php
namespace Opencart\Catalog\Controller\Checkout;
/**
 * Class Register
 *
 * Can be loaded using $this->load->controller('checkout/register');
 *
 * @package Opencart\Catalog\Controller\Checkout
 */
class Register extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('checkout/register');

		$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', 'language=' . $this->config->get('config_language') . '&redirect=' . urlencode($this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'), true))));

		$data['entry_newsletter'] = sprintf($this->language->get('entry_newsletter'), $this->config->get('config_name'));

		$data['error_upload_size'] = sprintf($this->language->get('error_upload_size'), $this->config->get('config_file_max_size'));

		$data['config_checkout_payment_address'] = $this->config->get('config_checkout_payment_address');
		$data['config_checkout_guest'] = ($this->config->get('config_checkout_guest') && !$this->config->get('config_customer_price') && !$this->cart->hasDownload() && !$this->cart->hasSubscription());
		$data['config_file_max_size'] = ((int)$this->config->get('config_file_max_size') * 1024 * 1024);
		$data['config_telephone_display'] = $this->config->get('config_telephone_display');
		$data['config_telephone_required'] = $this->config->get('config_telephone_required');

		$data['shipping_required'] = $this->cart->hasShipping();

		$this->session->data['upload_token'] = oc_token(32);

		$data['upload'] = $this->url->link('tool/upload', 'language=' . $this->config->get('config_language') . '&upload_token=' . $this->session->data['upload_token']);

		// Customer Groups
		$data['customer_groups'] = [];

		if (is_array($this->config->get('config_customer_group_display'))) {
			$this->load->model('account/customer_group');

			$customer_groups = $this->model_account_customer_group->getCustomerGroups();

			foreach ($customer_groups as $customer_group) {
				if (in_array($customer_group['customer_group_id'], (array)$this->config->get('config_customer_group_display'))) {
					$data['customer_groups'][] = $customer_group;
				}
			}
		}

		if (isset($this->session->data['customer']['customer_id'])) {
			$data['account'] = $this->session->data['customer']['customer_id'];
		} else {
			$data['account'] = 1;
		}

		if (isset($this->session->data['customer'])) {
			$data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
			$data['firstname'] = $this->session->data['customer']['firstname'];
			$data['lastname'] = $this->session->data['customer']['lastname'];
			$data['email'] = $this->session->data['customer']['email'];
			$data['telephone'] = $this->session->data['customer']['telephone'];
			$data['account_custom_field'] = $this->session->data['customer']['custom_field'];
		} else {
			$data['customer_group_id'] = (int)$this->config->get('config_customer_group_id');
			$data['firstname'] = '';
			$data['lastname'] = '';
			$data['email'] = '';
			$data['telephone'] = '';
			$data['account_custom_field'] = [];
		}

		if (isset($this->session->data['payment_address'])) {
			$data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
			$data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
			$data['payment_company'] = $this->session->data['payment_address']['company'];
			$data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
			$data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
			$data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
			$data['payment_city'] = $this->session->data['payment_address']['city'];
			$data['payment_country_id'] = (int)$this->session->data['payment_address']['country_id'];
			$data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
			$data['payment_custom_field'] = $this->session->data['payment_address']['custom_field'];
		} else {
			$data['payment_firstname'] = '';
			$data['payment_lastname'] = '';
			$data['payment_company'] = '';
			$data['payment_address_1'] = '';
			$data['payment_address_2'] = '';
			$data['payment_postcode'] = '';
			$data['payment_city'] = '';
			$data['payment_country_id'] = (int)$this->config->get('config_country_id');
			$data['payment_zone_id'] = 0;
			$data['payment_custom_field'] = [];
		}

		if (isset($this->session->data['shipping_address']['address_id'])) {
			$data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
			$data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
			$data['shipping_company'] = $this->session->data['shipping_address']['company'];
			$data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
			$data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
			$data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
			$data['shipping_city'] = $this->session->data['shipping_address']['city'];
			$data['shipping_country_id'] = (int)$this->session->data['shipping_address']['country_id'];
			$data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
			$data['shipping_custom_field'] = $this->session->data['shipping_address']['custom_field'];
		} else {
			$data['shipping_firstname'] = '';
			$data['shipping_lastname'] = '';
			$data['shipping_company'] = '';
			$data['shipping_address_1'] = '';
			$data['shipping_address_2'] = '';

			if (isset($this->session->data['shipping_address']['postcode'])) {
				$data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
			} else {
				$data['shipping_postcode'] = '';
			}

			$data['shipping_city'] = '';

			if (isset($this->session->data['shipping_address']['country_id'])) {
				$data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
			} else {
				$data['shipping_country_id'] = (int)$this->config->get('config_country_id');
			}

			if (isset($this->session->data['shipping_address']['zone_id'])) {
				$data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
			} else {
				$data['shipping_zone_id'] = 0;
			}

			$data['shipping_custom_field'] = [];
		}

		// Custom Fields
		$this->load->model('account/custom_field');

		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields();

		// Captcha
		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

		if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/' . $extension_info['extension'] . '/captcha/' . $extension_info['code']);
		} else {
			$data['captcha'] = '';
		}

		// Information
		$this->load->model('catalog/information');

		$information_info = $this->model_catalog_information->getInformation((int)$this->config->get('config_account_id'));

		if ($information_info) {
			$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information.info', 'language=' . $this->config->get('config_language') . '&information_id=' . $this->config->get('config_account_id')), $information_info['title']);
		} else {
			$data['text_agree'] = '';
		}

		$data['language'] = $this->config->get('config_language');

		return $this->load->view('checkout/register', $data);
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('checkout/register');

		$json = [];

		$required = [
			'account'               => 0,
			'customer_group_id'     => 0,
			'firstname'             => '',
			'lastname'              => '',
			'email'                 => '',
			'telephone'             => '',
			'payment_company'       => '',
			'payment_address_1'     => '',
			'payment_address_2'     => '',
			'payment_city'          => '',
			'payment_postcode'      => '',
			'payment_country_id'    => 0,
			'payment_zone_id'       => 0,
			'payment_custom_field'  => [],
			'address_match'         => 0,
			'shipping_firstname'    => '',
			'shipping_lastname'     => '',
			'shipping_company'      => '',
			'shipping_address_1'    => '',
			'shipping_address_2'    => '',
			'shipping_city'         => '',
			'shipping_postcode'     => '',
			'shipping_country_id'   => 0,
			'shipping_zone_id'      => 0,
			'shipping_custom_field' => [],
			'password'              => '',
			'agree'                 => 0
		];

		$post_info = $this->request->post + $required;

		// Force account requires subscript or is a downloadable product.
		if ($this->cart->hasDownload() || $this->cart->hasSubscription() || !$this->config->get('config_checkout_guest')) {
			$post_info['account'] = 1;
		}

		// Validate cart has products and has stock.
		if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout')) || !$this->cart->hasMinimum()) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			// If not guest checkout disabled, login require price or cart has downloads
			if (!$post_info['account'] && (!$this->config->get('config_checkout_guest') || $this->config->get('config_customer_price'))) {
				$json['error']['warning'] = $this->language->get('error_guest');
			}

			// Customer Group
			if ($post_info['customer_group_id']) {
				$customer_group_id = (int)$post_info['customer_group_id'];
			} else {
				$customer_group_id = (int)$post_info('config_customer_group_id');
			}

			$this->load->model('account/customer_group');

			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

			if (!$customer_group_info || !in_array($customer_group_id, (array)$this->config->get('config_customer_group_display'))) {
				$json['error']['warning'] = $this->language->get('error_customer_group');
			}

			if (!oc_validate_length($post_info['firstname'], 1, 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}

			if (!oc_validate_length($post_info['lastname'], 1, 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}

			if (!oc_validate_email($post_info['email'])) {
				$json['error']['email'] = $this->language->get('error_email');
			}

			// Total Customers
			$this->load->model('account/customer');

			if ($post_info['account'] && $this->model_account_customer->getTotalCustomersByEmail($post_info['email'])) {
				$json['error']['warning'] = $this->language->get('error_exists');
			}

			// Logged in, so add customer details
			if ($this->customer->isLogged()) {
				$customer_info = $this->model_account_customer->getCustomerByEmail($post_info['email']);

				if ($customer_info['customer_id'] != $this->customer->getId()) {
					$json['error']['warning'] = $this->language->get('error_exists');
				}
			}

			if ($this->config->get('config_telephone_required') && !oc_validate_length($post_info['telephone'], 3, 32)) {
				$json['error']['telephone'] = $this->language->get('error_telephone');
			}

			// Custom fields validation
			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'account') {
					if ($custom_field['required'] && empty($post_info['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
						$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !oc_validate_regex($post_info['custom_field'][$custom_field['location']][$custom_field['custom_field_id']], $custom_field['validation'])) {
						$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
					}
				}
			}

			if ($this->config->get('config_checkout_payment_address')) {
				if (!oc_validate_length($post_info['payment_address_1'], 3, 128)) {
					$json['error']['payment_address_1'] = $this->language->get('error_address_1');
				}

				if (!oc_validate_length($post_info['payment_city'], 2, 128)) {
					$json['error']['payment_city'] = $this->language->get('error_city');
				}

				// Country
				$this->load->model('localisation/country');

				$payment_country_info = $this->model_localisation_country->getCountry((int)$post_info['payment_country_id']);

				if ($payment_country_info && $payment_country_info['postcode_required'] && !oc_validate_length($post_info['payment_postcode'], 2, 10)) {
					$json['error']['payment_postcode'] = $this->language->get('error_postcode');
				}

				if (!$payment_country_info) {
					$json['error']['payment_country'] = $this->language->get('error_country');
				}

				// Zones
				$this->load->model('localisation/zone');

				// Total Zones
				$zone_total = $this->model_localisation_zone->getTotalZonesByCountryId((int)$post_info['payment_country_id']);

				if ($zone_total && !$post_info['payment_zone_id']) {
					$json['error']['payment_zone'] = $this->language->get('error_zone');
				}

				// Custom fields validation
				foreach ($custom_fields as $custom_field) {
					if ($custom_field['location'] == 'address') {
						if ($custom_field['required'] && empty($post_info['payment_custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
							$json['error']['payment_custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
						} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !oc_validate_regex($post_info['payment_custom_field'][$custom_field['location']][$custom_field['custom_field_id']], $custom_field['validation'])) {
							$json['error']['payment_custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
						}
					}
				}
			}

			if ($this->cart->hasShipping() && !$post_info['address_match']) {
				// If payment address not required we need to use the firstname and lastname from the account.
				if ($this->config->get('config_checkout_payment_address')) {
					if (!oc_validate_length($post_info['shipping_firstname'], 1, 32)) {
						$json['error']['shipping_firstname'] = $this->language->get('error_firstname');
					}

					if (!oc_validate_length($post_info['shipping_lastname'], 1, 32)) {
						$json['error']['shipping_lastname'] = $this->language->get('error_lastname');
					}
				}

				if (!oc_validate_length($post_info['shipping_address_1'], 3, 128)) {
					$json['error']['shipping_address_1'] = $this->language->get('error_address_1');
				}

				if (!oc_validate_length($post_info['shipping_city'], 2, 128)) {
					$json['error']['shipping_city'] = $this->language->get('error_city');
				}

				// Country
				$this->load->model('localisation/country');

				$shipping_country_info = $this->model_localisation_country->getCountry((int)$post_info['shipping_country_id']);

				if ($shipping_country_info && $shipping_country_info['postcode_required'] && !oc_validate_length($post_info['shipping_postcode'], 2, 10)) {
					$json['error']['shipping_postcode'] = $this->language->get('error_postcode');
				}

				if (!$shipping_country_info) {
					$json['error']['shipping_country'] = $this->language->get('error_country');
				}

				// Zones
				$this->load->model('localisation/zone');

				// Total Zones
				$zone_total = $this->model_localisation_zone->getTotalZonesByCountryId((int)$post_info['shipping_country_id']);

				if ($zone_total && !$post_info['shipping_zone_id']) {
					$json['error']['shipping_zone'] = $this->language->get('error_zone');
				}

				// Custom fields validation
				foreach ($custom_fields as $custom_field) {
					if ($custom_field['location'] == 'address') {
						if ($custom_field['required'] && empty($post_info['shipping_custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
							$json['error']['shipping_custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
						} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !oc_validate_regex($post_info['shipping_custom_field'][$custom_field['location']][$custom_field['custom_field_id']], $custom_field['validation'])) {
							$json['error']['shipping_custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
						}
					}
				}
			}

			// If account register password required
			if ($post_info['account']) {
				$password = html_entity_decode($post_info['password'], ENT_QUOTES, 'UTF-8');

				if (!oc_validate_length($password, (int)$this->config->get('config_password_length'), 40)) {
					$json['error']['password'] = sprintf($this->language->get('error_password_length'), $this->config->get('config_password_length'));
				}

				$required = [];

				if ($this->config->get('config_password_uppercase') && !preg_match('/[A-Z]/', $password)) {
					$required[] = $this->language->get('error_password_uppercase');
				}

				if ($this->config->get('config_password_lowercase') && !preg_match('/[a-z]/', $password)) {
					$required[] = $this->language->get('error_password_lowercase');
				}

				if ($this->config->get('config_password_number') && !preg_match('/[0-9]/', $password)) {
					$required[] = $this->language->get('error_password_number');
				}

				if ($this->config->get('config_password_symbol') && !preg_match('/[^a-zA-Z0-9]/', $password)) {
					$required[] = $this->language->get('error_password_symbol');
				}

				if ($required) {
					$json['error']['password'] = sprintf($this->language->get('error_password'), implode(', ', $required), $this->config->get('config_password_length'));
				}

				// Agree to terms
				$this->load->model('catalog/information');

				$information_info = $this->model_catalog_information->getInformation((int)$this->config->get('config_account_id'));

				if ($information_info && !$post_info['agree']) {
					$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
				}
			}

			// Captcha
			$this->load->model('setting/extension');

			if (!$this->customer->isLogged()) {
				$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

				if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
					$captcha = $this->load->controller('extension/' . $extension_info['extension'] . '/captcha/' . $extension_info['code'] . '.validate');

					if ($captcha) {
						$json['error']['captcha'] = $captcha;
					}
				}
			}
		}

		if (!$json) {
			// Add customer details into session
			$customer_data = [
				'customer_id'       => 0,
				'customer_group_id' => $customer_group_id,
				'firstname'         => $post_info['firstname'],
				'lastname'          => $post_info['lastname'],
				'email'             => $post_info['email'],
				'telephone'         => $post_info['telephone'],
				'custom_field'      => $post_info['custom_field'] ?? []
			];

			// Register
			if ($post_info['account']) {
				$customer_data['customer_id'] = $this->model_account_customer->addCustomer($post_info);
			}

			// Logged in, so edit customer details
			if ($this->customer->isLogged()) {
				$this->model_account_customer->editCustomer($this->customer->getId(), $post_info);
			}

			// Check if current customer group requires approval
			if (!$customer_group_info['approval']) {
				$this->session->data['customer'] = $customer_data;
			}

			// Payment Address
			$this->load->model('account/address');

			if ($this->config->get('config_checkout_payment_address')) {
				if (isset($this->session->data['payment_address']['address_id'])) {
					$address_id = $this->session->data['payment_address']['address_id'];
				} else {
					$address_id = 0;
				}

				if ($payment_country_info) {
					$country = $payment_country_info['name'];
					$iso_code_2 = $payment_country_info['iso_code_2'];
					$iso_code_3 = $payment_country_info['iso_code_3'];
					$address_format_id = $payment_country_info['address_format_id'];
				} else {
					$country = '';
					$iso_code_2 = '';
					$iso_code_3 = '';
					$address_format_id = 0;
				}

				// Address Format
				$this->load->model('localisation/address_format');

				$address_format_info = $this->model_localisation_address_format->getAddressFormat($address_format_id);

				if ($address_format_info) {
					$address_format = $address_format_info['address_format'];
				} else {
					$address_format = '';
				}

				// Zone
				$this->load->model('localisation/zone');

				$zone_info = $this->model_localisation_zone->getZone($post_info['payment_zone_id']);

				if ($zone_info) {
					$zone = $zone_info['name'];
					$zone_code = $zone_info['code'];
				} else {
					$zone = '';
					$zone_code = '';
				}

				$payment_address_data = [
					'address_id'     => $address_id,
					'firstname'      => $post_info['firstname'],
					'lastname'       => $post_info['lastname'],
					'company'        => $post_info['payment_company'],
					'address_1'      => $post_info['payment_address_1'],
					'address_2'      => $post_info['payment_address_2'],
					'city'           => $post_info['payment_city'],
					'postcode'       => $post_info['payment_postcode'],
					'zone_id'        => $post_info['payment_zone_id'],
					'zone'           => $zone,
					'zone_code'      => $zone_code,
					'country_id'     => $post_info['payment_country_id'],
					'country'        => $country,
					'iso_code_2'     => $iso_code_2,
					'iso_code_3'     => $iso_code_3,
					'address_format' => $address_format,
					'custom_field'   => $post_info['payment_custom_field'] ?? []
				];

				// Add
				if ($post_info['account']) {
					$payment_address_data['default'] = 1;

					$payment_address_data['address_id'] = $this->model_account_address->addAddress($customer_data['customer_id'], $payment_address_data);
				}

				// Edit
				if ($this->customer->isLogged() && $payment_address_data['address_id']) {
					$this->model_account_address->editAddress($this->customer->getId(), $payment_address_data['address_id'], $payment_address_data);
				}

				// Requires Approval
				if (!$customer_group_info['approval']) {
					$this->session->data['payment_address'] = $payment_address_data;
				}
			}

			// Shipping Address
			if ($this->cart->hasShipping()) {
				if (!$post_info['address_match']) {
					if (isset($this->session->data['shipping_address']['address_id'])) {
						$address_id = $this->session->data['shipping_address']['address_id'];
					} else {
						$address_id = 0;
					}

					if (!$this->config->get('config_checkout_payment_address')) {
						$firstname = $post_info['firstname'];
						$lastname = $post_info['lastname'];
					} else {
						$firstname = $post_info['shipping_firstname'];
						$lastname = $post_info['shipping_lastname'];
					}

					// Address Format
					if ($shipping_country_info) {
						$country = $shipping_country_info['name'];
						$iso_code_2 = $shipping_country_info['iso_code_2'];
						$iso_code_3 = $shipping_country_info['iso_code_3'];
						$address_format_id = $shipping_country_info['address_format_id'];
					} else {
						$country = '';
						$iso_code_2 = '';
						$iso_code_3 = '';
						$address_format_id = 0;
					}

					$this->load->model('localisation/address_format');

					$address_format_info = $this->model_localisation_address_format->getAddressFormat($address_format_id);

					if ($address_format_info) {
						$address_format = $address_format_info['address_format'];
					} else {
						$address_format = '';
					}

					// Zone
					$this->load->model('localisation/zone');

					$zone_info = $this->model_localisation_zone->getZone($post_info['shipping_zone_id']);

					if ($zone_info) {
						$zone = $zone_info['name'];
						$zone_code = $zone_info['code'];
					} else {
						$zone = '';
						$zone_code = '';
					}

					$shipping_address_data = [
						'address_id'     => $address_id,
						'firstname'      => $firstname,
						'lastname'       => $lastname,
						'company'        => $post_info['shipping_company'],
						'address_1'      => $post_info['shipping_address_1'],
						'address_2'      => $post_info['shipping_address_2'],
						'city'           => $post_info['shipping_city'],
						'postcode'       => $post_info['shipping_postcode'],
						'zone_id'        => $post_info['shipping_zone_id'],
						'zone'           => $zone,
						'zone_code'      => $zone_code,
						'country_id'     => $post_info['shipping_country_id'],
						'country'        => $country,
						'iso_code_2'     => $iso_code_2,
						'iso_code_3'     => $iso_code_3,
						'address_format' => $address_format,
						'custom_field'   => $post_info['shipping_custom_field'] ?? []
					];

					// Add Address to account if account is being created.
					if ($post_info['account']) {
						if (!$this->config->get('config_checkout_payment_address')) {
							$shipping_address_data['default'] = 1;
						}

						$shipping_address_data['address_id'] = $this->model_account_address->addAddress($customer_data['customer_id'], $shipping_address_data);
					}

					// Edit
					if ($this->customer->isLogged() && $shipping_address_data['address_id']) {
						$this->model_account_address->editAddress($this->customer->getId(), $shipping_address_data['address_id'], $shipping_address_data);
					}

					// Requires Approval
					if (!$customer_group_info['approval']) {
						$this->session->data['shipping_address'] = $shipping_address_data;
					}
				} elseif (!$customer_group_info['approval'] && $this->config->get('config_checkout_payment_address')) {
					$this->session->data['shipping_address'] = $this->session->data['payment_address'];

					// Remove the address id so if the customer changes their mind and requires changing a different shipping address it will create a new address.
					$this->session->data['shipping_address']['address_id'] = 0;
				}
			}

			// If everything good login
			if (!$customer_group_info['approval']) {
				if ($post_info['account']) {
					$this->customer->login($post_info['email'], $password);

					// Create customer token
					$this->session->data['customer_token'] = oc_token(26);

					$json['success'] = $this->language->get('text_success_add');
				} elseif ($this->customer->isLogged()) {
					$json['success'] = $this->language->get('text_success_edit');
				} else {
					$json['success'] = $this->language->get('text_success_guest');
				}
			} else {
				// If account needs approval, we redirect to the account success / requires approval page.
				$json['redirect'] = $this->url->link('account/success', 'language=' . $this->config->get('config_language'), true);
			}

			unset($this->session->data['order_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);

			// Clear any previous login attempts for unregistered accounts.
			$this->model_account_customer->deleteLoginAttempts($post_info['email']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
