<?php
namespace Opencart\catalog\controller\api;
/**
 * Class Order
 *
 * @package Opencart\Catalog\Controller\Api
 */
class Order extends \Opencart\System\Engine\Controller {
	private $filter = [
		//'language'          => FILTER_VALIDATE_STRING,
		//'customer_id'       => FILTER_VALIDATE_INT,
		//'customer_group_id' => FILTER_VALIDATE_INT,
		//'firstname'         => FILTER_SANITIZE_STRING
	];

	public function save(): void {
		$this->load->language('api/sale/order');

		$json = [];

		// Add keys for missing post vars
		$keys = [
			'order_id',
			'language',
			'currency',
			'customer_id',
			'customer_group_id',
			'firstname',
			'lastname',
			'email',
			'telephone',
			'custom_field',
			'payment_address_id',
			'payment_firstname',
			'payment_lastname',
			'payment_company',
			'payment_address_1',
			'payment_address_2',
			'payment_postcode',
			'payment_city',
			'payment_zone_id',
			'payment_country_id',
			'payment_custom_field',
			'payment_method',
			'shipping_address_id',
			'shipping_firstname',
			'shipping_lastname',
			'shipping_company',
			'shipping_address_1',
			'shipping_address_2',
			'shipping_postcode',
			'shipping_city',
			'shipping_zone_id',
			'shipping_country_id',
			'shipping_custom_field',
			'shipping_method',
			'products',
			'vouchers',
			'comment',
			'order_status_id',
			'affiliate_id',
			'marketing_id',
			'language',
			'currency'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		if (!$json) {
			$this->session->data['order_id'] = 0;
		}

		// Customer
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
			$customer_group_id = (int)$this->config->get('config_customer_group_id');
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

		if (!$customer_group_info) {
			$json['error']['warning'] = $this->language->get('error_customer_group');
		}

		if (!oc_validate_length($this->request->post['firstname'], 1, 32)) {
			$json['error']['firstname'] = $this->language->get('error_firstname');
		}

		if (!oc_validate_length($this->request->post['lastname'], 1, 32)) {
			$json['error']['lastname'] = $this->language->get('error_lastname');
		}

		if (!oc_validate_email($this->request->post['email'])) {
			$json['error']['email'] = $this->language->get('error_email');
		}

		if ($this->config->get('config_telephone_required') && !oc_validate_length($this->request->post['telephone'], 3, 32)) {
			$json['error']['telephone'] = $this->language->get('error_telephone');
		}

		// Custom field validation
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields((int)$customer_group_id);

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'account') {
				if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
					$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !oc_validate_regex($this->request->post['custom_field'][$custom_field['custom_field_id']], $custom_field['validation'])) {
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

			// Customer Group
			$this->config->set('config_customer_group_id', $this->request->post['customer_group_id']);
		}

		// Currency
		if (isset($this->request->post['currency'])) {
			$currency = (string)$this->request->post['currency'];
		} else {
			$currency = $this->config->get('config_currency');
		}

		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrencyByCode($currency);

		if (!$currency_info) {
			$json['error']['currency'] = $this->language->get('error_currency');
		}

		if (!$json) {
			$this->session->data['currency'] = $currency;
		}










		// Products
		if (!empty($this->session->data['products'])) {
			$this->load->model('catalog/product');

			foreach ($this->request->post['products'] as $product) {



				if (isset($product['quantity'])) {
					$quantity = (int)$product['quantity'];
				} else {
					$quantity = 1;
				}

				if (isset($product['option'])) {
					$option = array_filter($product['option']);
				} else {
					$option = [];
				}

				if (isset($product['subscription_plan_id'])) {
					$subscription_plan_id = (int)$product['subscription_plan_id'];
				} else {
					$subscription_plan_id = 0;
				}

				$product_info = $this->model_catalog_product->getProduct($product['product_id']);

				if ($product_info) {
					// If variant get master product
					if ($product_info['master_id']) {
						$product_id = $product_info['master_id'];
					}

					// Merge variant code with options
					foreach ($product_info['variant'] as $key => $value) {
						$option[$key] = $value;
					}

					// Validate options
					$product_options = $this->model_catalog_product->getOptions($product['product_id']);

					foreach ($product_options as $product_option) {
						if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
							$json['error']['option_' . $product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
						}
					}

					// Validate Subscription plan
					$subscriptions = $this->model_catalog_product->getSubscriptions($product['product_id']);

					if ($subscriptions) {
						$subscription_plan_ids = [];

						foreach ($subscriptions as $subscription) {
							$subscription_plan_ids[] = $subscription['subscription_plan_id'];
						}

						if (!in_array($subscription_plan_id, $subscription_plan_ids)) {
							$json['error']['subscription'][$key] = $this->language->get('error_subscription');
						}
					}
				} else {
					$json['error']['warning'] = $this->language->get('error_product');
				}
			}

			// Gift Voucher
			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $key => $voucher) {
					$keys = [
						'to_name',
						'to_email',
						'from_name',
						'from_email',
						'voucher_theme_id',
						'amount',
						'agree'
					];

					foreach ($keys as $key) {
						if (!isset($this->request->post[$key])) {
							$this->request->post[$key] = '';
						}
					}

					if (!isset($this->request->get['voucher_token']) || !isset($this->session->data['voucher_token']) || ($this->session->data['voucher_token'] != $this->request->get['voucher_token'])) {
						$json['redirect'] = $this->url->link('checkout/voucher', 'language=' . $this->config->get('config_language'), true);
					}

					if (!oc_validate_length($this->request->post['to_name'], 1, 64)) {
						$json['error']['to_name'] = $this->language->get('error_to_name');
					}

					if ((oc_strlen($this->request->post['to_email']) > 96) || !filter_var($this->request->post['to_email'], FILTER_VALIDATE_EMAIL)) {
						$json['error']['to_email'] = $this->language->get('error_email');
					}

					if (!oc_validate_length($this->request->post['from_name'], 1, 64)) {
						$json['error']['from_name'] = $this->language->get('error_from_name');
					}

					if ((oc_strlen($this->request->post['from_email']) > 96) || !filter_var($this->request->post['from_email'], FILTER_VALIDATE_EMAIL)) {
						$json['error']['from_email'] = $this->language->get('error_email');
					}

					if (!$this->request->post['voucher_theme_id']) {
						$json['error']['theme'] = $this->language->get('error_theme');
					}

					if (($this->currency->convert((int)$this->request->post['amount'], $this->session->data['currency'], $this->config->get('config_currency')) < $this->config->get('config_voucher_min')) || ($this->currency->convert($this->request->post['amount'], $this->session->data['currency'], $this->config->get('config_currency')) > $this->config->get('config_voucher_max'))) {
						$json['error']['amount'] = sprintf($this->language->get('error_amount'), $this->currency->format($this->config->get('config_voucher_min'), $this->session->data['currency']), $this->currency->format($this->config->get('config_voucher_max'), $this->session->data['currency']));
					}

					if (empty($this->request->post['agree'])) {
						$json['error']['warning'] = $this->language->get('error_agree');
					}

					$this->session->data['vouchers'][] = [
						'code'             => oc_token(10),
						'description'      => sprintf($this->language->get('text_for'), $this->currency->format($this->request->post['amount'], $this->session->data['currency'], 1.0), $this->request->post['to_name']),
						'to_name'          => $this->request->post['to_name'],
						'to_email'         => $this->request->post['to_email'],
						'from_name'        => $this->request->post['from_name'],
						'from_email'       => $this->request->post['from_email'],
						'voucher_theme_id' => $this->request->post['voucher_theme_id'],
						'message'          => $this->request->post['message'],
						'amount'           => $this->currency->convert((int)$this->request->post['amount'], $this->session->data['currency'], $this->config->get('config_currency'))
					];
				}
			}
		}

		// Payment Address
		if ($this->config->get('config_payment_address')) {
			if (!oc_validate_length($this->request->post['payment_firstname'], 1, 32)) {
				$json['error']['payment_firstname'] = $this->language->get('error_firstname');
			}

			if (!oc_validate_length($this->request->post['payment_lastname'], 1, 32)) {
				$json['error']['payment_lastname'] = $this->language->get('error_lastname');
			}

			if (!oc_validate_length($this->request->post['payment_address_1'], 3, 128)) {
				$json['error']['payment_address_1'] = $this->language->get('error_address_1');
			}

			if (!oc_validate_length($this->request->post['payment_city'], 2, 128)) {
				$json['error']['payment_city'] = $this->language->get('error_city');
			}

			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry((int)$this->request->post['payment_country_id']);

			if ($country_info && $country_info['postcode_required'] && !oc_validate_length($this->request->post['payment_postcode'], 2, 10)) {
				$json['error']['payment_postcode'] = $this->language->get('error_postcode');
			}

			if (!$country_info || $this->request->post['payment_country_id'] == '') {
				$json['error']['payment_country'] = $this->language->get('error_country');
			}

			if ($this->request->post['payment_zone_id'] == '') {
				$json['error']['payment_zone'] = $this->language->get('error_zone');
			}

			// Custom field validation
			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address') {
					if ($custom_field['required'] && empty($this->request->post['payment_custom_field'][$custom_field['custom_field_id']])) {
						$json['error']['payment_custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !oc_validate_regex($this->request->post['payment_custom_field'][$custom_field['custom_field_id']], $custom_field['validation'])) {
						$json['error']['payment_custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
					}
				}
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

				$zone_info = $this->model_localisation_zone->getZone($this->request->post['payment_zone_id']);

				if ($zone_info) {
					$zone = $zone_info['name'];
					$zone_code = $zone_info['code'];
				} else {
					$zone = '';
					$zone_code = '';
				}

				$this->session->data['payment_address'] = [
					'address_id'     => $this->request->post['payment_address_id'],
					'firstname'      => $this->request->post['payment_firstname'],
					'lastname'       => $this->request->post['payment_lastname'],
					'company'        => $this->request->post['payment_company'],
					'address_1'      => $this->request->post['payment_address_1'],
					'address_2'      => $this->request->post['payment_address_2'],
					'postcode'       => $this->request->post['payment_postcode'],
					'city'           => $this->request->post['payment_city'],
					'zone_id'        => $this->request->post['payment_zone_id'],
					'zone'           => $zone,
					'zone_code'      => $zone_code,
					'country_id'     => (int)$this->request->post['payment_country_id'],
					'country'        => $country,
					'iso_code_2'     => $iso_code_2,
					'iso_code_3'     => $iso_code_3,
					'address_format' => $address_format,
					'custom_field'   => $this->request->post['payment_custom_field'] ?? []
				];
			}
		}

		// Shipping address
		if ($this->cart->hasShipping()) {
			if (!oc_validate_length($this->request->post['shipping_firstname'], 1, 32)) {
				$json['error']['shipping_firstname'] = $this->language->get('error_firstname');
			}

			if (!oc_validate_length($this->request->post['shipping_lastname'], 1, 32)) {
				$json['error']['shipping_lastname'] = $this->language->get('error_lastname');
			}

			if (!oc_validate_length($this->request->post['shipping_address_1'], 3, 128)) {
				$json['error']['shipping_address_1'] = $this->language->get('error_address_1');
			}

			if (!oc_validate_length($this->request->post['shipping_city'], 2, 128)) {
				$json['error']['shipping_city'] = $this->language->get('error_city');
			}

			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry((int)$this->request->post['shipping_country_id']);

			if ($country_info && $country_info['postcode_required'] && !oc_validate_length($this->request->post['shipping_postcode'], 2, 10)) {
				$json['error']['shipping_postcode'] = $this->language->get('error_postcode');
			}

			if (!$country_info || $this->request->post['shipping_country_id'] == '') {
				$json['error']['shipping_country'] = $this->language->get('error_country');
			}

			if ($this->request->post['shipping_zone_id'] == '') {
				$json['error']['shipping_zone'] = $this->language->get('error_zone');
			}

			// Custom field validation
			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address') {
					if ($custom_field['required'] && empty($this->request->post['shipping_custom_field'][$custom_field['custom_field_id']])) {
						$json['error']['shipping_custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !oc_validate_regex($this->request->post['shipping_custom_field'][$custom_field['custom_field_id']], $custom_field['validation'])) {
						$json['error']['shipping_custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
					}
				}
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

				$zone_info = $this->model_localisation_zone->getZone($this->request->post['shipping_zone_id']);

				if ($zone_info) {
					$zone = $zone_info['name'];
					$zone_code = $zone_info['code'];
				} else {
					$zone = '';
					$zone_code = '';
				}

				$this->session->data['shipping_address'] = [
					'address_id'     => $this->request->post['shipping_address_id'],
					'firstname'      => $this->request->post['shipping_firstname'],
					'lastname'       => $this->request->post['shipping_lastname'],
					'company'        => $this->request->post['shipping_company'],
					'address_1'      => $this->request->post['shipping_address_1'],
					'address_2'      => $this->request->post['shipping_address_2'],
					'postcode'       => $this->request->post['shipping_postcode'],
					'city'           => $this->request->post['shipping_city'],
					'zone_id'        => $this->request->post['shipping_zone_id'],
					'zone'           => $zone,
					'zone_code'      => $zone_code,
					'country_id'     => (int)$this->request->post['shipping_country_id'],
					'country'        => $country,
					'iso_code_2'     => $iso_code_2,
					'iso_code_3'     => $iso_code_3,
					'address_format' => $address_format,
					'custom_field'   => $this->request->post['shipping_custom_field'] ?? []
				];
			}
		}


		if (!$json) {
			$order_data = [];

			// Store Details
			$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');

			$order_data['store_id'] = $this->config->get('config_store_id');
			$order_data['store_name'] = $this->config->get('config_name');
			$order_data['store_url'] = $this->config->get('config_url');

			// Customer Details
			$order_data['customer_id'] = $this->session->data['customer']['customer_id'];
			$order_data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
			$order_data['firstname'] = $this->session->data['customer']['firstname'];
			$order_data['lastname'] = $this->session->data['customer']['lastname'];
			$order_data['email'] = $this->session->data['customer']['email'];
			$order_data['telephone'] = $this->session->data['customer']['telephone'];
			$order_data['custom_field'] = $this->session->data['customer']['custom_field'];

			// Payment Details
			if ($this->config->get('config_checkout_payment_address')) {
				$order_data['payment_address_id'] = $this->session->data['payment_address']['address_id'];
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
				$order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
				$order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
				$order_data['payment_custom_field'] = $this->session->data['payment_address']['custom_field'] ?? [];
			} else {
				$order_data['payment_address_id'] = 0;
				$order_data['payment_firstname'] = '';
				$order_data['payment_lastname'] = '';
				$order_data['payment_company'] = '';
				$order_data['payment_address_1'] = '';
				$order_data['payment_address_2'] = '';
				$order_data['payment_city'] = '';
				$order_data['payment_postcode'] = '';
				$order_data['payment_zone'] = '';
				$order_data['payment_zone_id'] = 0;
				$order_data['payment_country'] = '';
				$order_data['payment_country_id'] = 0;
				$order_data['payment_address_format'] = '';
				$order_data['payment_custom_field'] = [];
			}

			$order_data['payment_method'] = $this->session->data['payment_method'];

			// Shipping Details
			if ($this->cart->hasShipping()) {
				$order_data['shipping_address_id'] = $this->session->data['shipping_address']['address_id'];
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
				$order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
				$order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
				$order_data['shipping_custom_field'] = $this->session->data['shipping_address']['custom_field'] ?? [];

				$order_data['shipping_method'] = $this->session->data['shipping_method'];
			} else {
				$order_data['shipping_address_id'] = 0;
				$order_data['shipping_firstname'] = '';
				$order_data['shipping_lastname'] = '';
				$order_data['shipping_company'] = '';
				$order_data['shipping_address_1'] = '';
				$order_data['shipping_address_2'] = '';
				$order_data['shipping_city'] = '';
				$order_data['shipping_postcode'] = '';
				$order_data['shipping_zone'] = '';
				$order_data['shipping_zone_id'] = 0;
				$order_data['shipping_country'] = '';
				$order_data['shipping_country_id'] = 0;
				$order_data['shipping_address_format'] = '';
				$order_data['shipping_custom_field'] = [];

				$order_data['shipping_method'] = [];
			}

			$points = 0;

			// Products
			$order_data['products'] = [];

			foreach ($this->cart->getProducts() as $product) {
				$option_data = [];

				foreach ($product['option'] as $option) {
					$option_data[] = [
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id'               => $option['option_id'],
						'option_value_id'         => $option['option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					];
				}

				$subscription_data = [];

				if ($product['subscription']) {
					$subscription_data = [
						'subscription_plan_id' => $product['subscription']['subscription_plan_id'],
						'name'                 => $product['subscription']['name'],
						'trial_frequency'      => $product['subscription']['trial_frequency'],
						'trial_cycle'          => $product['subscription']['trial_cycle'],
						'trial_duration'       => $product['subscription']['trial_duration'],
						'trial_remaining'      => $product['subscription']['trial_remaining'],
						'trial_status'         => $product['subscription']['trial_status'],
						'frequency'            => $product['subscription']['frequency'],
						'cycle'                => $product['subscription']['cycle'],
						'duration'             => $product['subscription']['duration']
					];
				}

				$order_data['products'][] = [
					'product_id'   => $product['product_id'],
					'master_id'    => $product['master_id'],
					'name'         => $product['name'],
					'model'        => $product['model'],
					'option'       => $option_data,
					'subscription' => $subscription_data,
					'download'     => $product['download'],
					'quantity'     => $product['quantity'],
					'subtract'     => $product['subtract'],
					'price'        => $product['price'],
					'total'        => $product['total'],
					'tax'          => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'       => $product['reward']
				];

				$points += $product['reward'];
			}

			// Gift Voucher
			$order_data['vouchers'] = [];

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$order_data['vouchers'][] = [
						'description'      => $voucher['description'],
						'code'             => oc_token(10),
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],
						'amount'           => $voucher['amount']
					];
				}
			}

			if (isset($this->session->data['comment'])) {
				$order_data['comment'] = $this->session->data['comment'];
			} else {
				$order_data['comment'] = '';
			}

			// Order Totals
			$totals = [];
			$taxes = $this->cart->getTaxes();
			$total = 0;

			$this->load->model('checkout/cart');

			($this->model_checkout_cart->getTotals)($totals, $taxes, $total);

			$total_data = [
				'totals' => $totals,
				'taxes'  => $taxes,
				'total'  => $total
			];

			$order_data = array_merge($order_data, $total_data);

			$order_data['affiliate_id'] = 0;
			$order_data['commission'] = 0;
			$order_data['marketing_id'] = 0;
			$order_data['tracking'] = '';

			if (isset($this->session->data['affiliate_id'])) {
				$subtotal = $this->cart->getSubTotal();

				// Affiliate
				$this->load->model('account/affiliate');

				$affiliate_info = $this->model_account_affiliate->getAffiliate($this->session->data['affiliate_id']);

				if ($affiliate_info) {
					$order_data['affiliate_id'] = $affiliate_info['customer_id'];
					$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
					$order_data['tracking'] = $affiliate_info['tracking'];
				}
			}

			// We use session to store language code for API access
			$order_data['language_id'] = $this->config->get('config_language_id');
			$order_data['language_code'] = $this->config->get('config_language');

			$order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
			$order_data['currency_code'] = $this->session->data['currency'];
			$order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);

			$order_data['ip'] = oc_get_ip();

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

			if (!isset($this->session->data['order_id'])) {
				$this->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);
			} else {
				$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

				if ($order_info) {
					$this->model_checkout_order->editOrder($this->session->data['order_id'], $order_data);
				}
			}

			$json['order_id'] = $this->session->data['order_id'];

			// Set the order history
			if (isset($this->request->post['order_status_id'])) {
				$order_status_id = (int)$this->request->post['order_status_id'];
			} else {
				$order_status_id = (int)$this->config->get('config_order_status_id');
			}

			$this->model_checkout_order->addHistory($json['order_id'], $order_status_id);

			$json['success'] = $this->language->get('text_success');

			$json['points'] = $points;

			if (isset($order_data['affiliate_id'])) {
				$json['commission'] = $this->currency->format($order_data['commission'], $this->config->get('config_currency'));
			}

			$json['success'] = $this->language->get('text_success');
		}








		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Load
	 *
	 * @return void
	 */
	public function load(): void {
		$this->load->language('api/sale/order');

		$json = [];

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if (!$order_info) {
			$json['error'] = $this->language->get('error_order');
		}

		if (!$json) {
			$this->session->data['order_id'] = $order_id;

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
			if ($this->config->get('config_checkout_payment_address')) {
				$this->session->data['payment_address'] = [
					'address_id'     => $order_info['payment_address_id'],
					'firstname'      => $order_info['payment_firstname'],
					'lastname'       => $order_info['payment_lastname'],
					'company'        => $order_info['payment_company'],
					'address_1'      => $order_info['payment_address_1'],
					'address_2'      => $order_info['payment_address_2'],
					'postcode'       => $order_info['payment_postcode'],
					'city'           => $order_info['payment_city'],
					'zone_id'        => $order_info['payment_zone_id'],
					'zone'           => $order_info['payment_zone'],
					'zone_code'      => $order_info['payment_zone_code'],
					'country_id'     => $order_info['payment_country_id'],
					'country'        => $order_info['payment_country'],
					'iso_code_2'     => $order_info['payment_iso_code_2'],
					'iso_code_3'     => $order_info['payment_iso_code_3'],
					'address_format' => $order_info['payment_address_format'],
					'custom_field'   => $order_info['payment_custom_field']
				];
			} else {
				unset($this->session->data['payment_address']);
			}

			$this->session->data['payment_method'] = $order_info['payment_method'];

			if ($order_info['shipping_method']) {
				$this->session->data['shipping_address'] = [
					'address_id'     => $order_info['shipping_address_id'],
					'firstname'      => $order_info['shipping_firstname'],
					'lastname'       => $order_info['shipping_lastname'],
					'company'        => $order_info['shipping_company'],
					'address_1'      => $order_info['shipping_address_1'],
					'address_2'      => $order_info['shipping_address_2'],
					'postcode'       => $order_info['shipping_postcode'],
					'city'           => $order_info['shipping_city'],
					'zone_id'        => $order_info['shipping_zone_id'],
					'zone'           => $order_info['shipping_zone'],
					'zone_code'      => $order_info['shipping_zone_code'],
					'country_id'     => $order_info['shipping_country_id'],
					'country'        => $order_info['shipping_country'],
					'iso_code_2'     => $order_info['shipping_iso_code_2'],
					'iso_code_3'     => $order_info['shipping_iso_code_3'],
					'address_format' => $order_info['shipping_address_format'],
					'custom_field'   => $order_info['shipping_custom_field']
				];

				$this->session->data['shipping_method'] = $order_info['shipping_method'];
			}

			if ($order_info['comment']) {
				$this->session->data['comment'] = $order_info['comment'];
			}

			if ($order_info['currency_code']) {
				$this->session->data['currency'] = $order_info['currency_code'];
			}

			$products = $this->model_checkout_order->getProducts($order_id);

			foreach ($products as $product) {
				$option_data = [];

				$options = $this->model_checkout_order->getOptions($order_id, $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
						$option_data[$option['product_option_id']] = $option['value'];
					} elseif ($option['type'] == 'select' || $option['type'] == 'radio') {
						$option_data[$option['product_option_id']] = $option['product_option_value_id'];
					} elseif ($option['type'] == 'checkbox') {
						$option_data[$option['product_option_id']][] = $option['product_option_value_id'];
					}
				}

				$subscription_info = $this->model_checkout_order->getSubscription($order_id, $product['order_product_id']);

				if ($subscription_info) {
					$subscription_plan_id = $subscription_info['subscription_plan_id'];
				} else {
					$subscription_plan_id = 0;
				}

				$this->cart->add($product['product_id'], $product['quantity'], $option_data, $subscription_plan_id, true, $product['price']);
			}

			$this->session->data['vouchers'] = [];

			$this->load->model('checkout/voucher');

			$vouchers = $this->model_checkout_order->getVouchers($order_id);

			foreach ($vouchers as $voucher) {
				$this->session->data['vouchers'][] = [
					'code'             => $voucher['code'],
					'description'      => sprintf($this->language->get('text_for'), $this->currency->format($voucher['amount'], $this->session->data['currency'], 1.0), $voucher['to_name']),
					'to_name'          => $voucher['to_name'],
					'to_email'         => $voucher['to_email'],
					'from_name'        => $voucher['from_name'],
					'from_email'       => $voucher['from_email'],
					'voucher_theme_id' => $voucher['voucher_theme_id'],
					'message'          => $voucher['message'],
					'amount'           => $this->currency->convert($voucher['amount'], $this->session->data['currency'], $this->config->get('config_currency'))
				];
			}

			if ($order_info['affiliate_id']) {
				$this->session->data['affiliate_id'] = $order_info['affiliate_id'];
			}

			// Coupon, Voucher, Reward
			$order_totals = $this->model_checkout_order->getTotals($order_id);

			foreach ($order_totals as $order_total) {
				// If coupon, voucher or reward points
				$start = strpos($order_total['title'], '(');
				$end = strrpos($order_total['title'], ')');

				if ($start !== false && $end !== false) {
					$this->session->data[$order_total['code']] = substr($order_total['title'], $start + 1, $end - ($start + 1));
				}
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Comment
	 *
	 * @return void
	 */
	public function comment(): void {
		$this->load->language('api/sale/order');

		$json = [];

		if (!isset($this->request->post['comment'])) {
			$json['error'] = $this->language->get('error_comment');
		}

		if (!$json) {
			$this->session->data['comment'] = $this->request->post['comment'];

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Confirm
	 *
	 * @return void
	 */
	public function confirm(): void {
		$this->load->language('api/sale/order');

		$json = [];

		// Validate cart has products and has stock.
		if (($this->cart->hasProducts() || !empty($this->session->data['vouchers']))) {
			if (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout')) {
				$json['error']['stock'] = $this->language->get('error_stock');
			}
		} else {
			$json['error']['product'] = $this->language->get('error_product');
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			if (!$product['minimum']) {
				$json['error']['minimum'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);

				break;
			}
		}

		// Customer
		if (!isset($this->session->data['customer'])) {
			$json['error']['customer'] = $this->language->get('error_customer');
		}

		// Payment Address
		if ($this->config->get('config_checkout_payment_address') && !isset($this->session->data['payment_address'])) {
			$json['error']['payment_address'] = $this->language->get('error_payment_address');
		}

		// Shipping
		if ($this->cart->hasShipping()) {
			// Shipping Address
			if (!isset($this->session->data['shipping_address'])) {
				$json['error']['shipping_address'] = $this->language->get('error_shipping_address');
			}

			// Validate shipping method
			if (!isset($this->session->data['shipping_method'])) {
				$json['error']['shipping_method'] = $this->language->get('error_shipping_method');
			}
		} else {
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		// Payment Method
		if (empty($this->session->data['payment_method'])) {
			$json['error']['payment_method'] = $this->language->get('error_payment_method');
		}

		if (!$json) {
			$order_data = [];

			// Store Details
			$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');

			$order_data['store_id'] = $this->config->get('config_store_id');
			$order_data['store_name'] = $this->config->get('config_name');
			$order_data['store_url'] = $this->config->get('config_url');

			// Customer Details
			$order_data['customer_id'] = $this->session->data['customer']['customer_id'];
			$order_data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
			$order_data['firstname'] = $this->session->data['customer']['firstname'];
			$order_data['lastname'] = $this->session->data['customer']['lastname'];
			$order_data['email'] = $this->session->data['customer']['email'];
			$order_data['telephone'] = $this->session->data['customer']['telephone'];
			$order_data['custom_field'] = $this->session->data['customer']['custom_field'];

			// Payment Details
			if ($this->config->get('config_checkout_payment_address')) {
				$order_data['payment_address_id'] = $this->session->data['payment_address']['address_id'];
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
				$order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
				$order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
				$order_data['payment_custom_field'] = $this->session->data['payment_address']['custom_field'] ?? [];
			} else {
				$order_data['payment_address_id'] = 0;
				$order_data['payment_firstname'] = '';
				$order_data['payment_lastname'] = '';
				$order_data['payment_company'] = '';
				$order_data['payment_address_1'] = '';
				$order_data['payment_address_2'] = '';
				$order_data['payment_city'] = '';
				$order_data['payment_postcode'] = '';
				$order_data['payment_zone'] = '';
				$order_data['payment_zone_id'] = 0;
				$order_data['payment_country'] = '';
				$order_data['payment_country_id'] = 0;
				$order_data['payment_address_format'] = '';
				$order_data['payment_custom_field'] = [];
			}

			$order_data['payment_method'] = $this->session->data['payment_method'];

			// Shipping Details
			if ($this->cart->hasShipping()) {
				$order_data['shipping_address_id'] = $this->session->data['shipping_address']['address_id'];
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
				$order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
				$order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
				$order_data['shipping_custom_field'] = $this->session->data['shipping_address']['custom_field'] ?? [];

				$order_data['shipping_method'] = $this->session->data['shipping_method'];
			} else {
				$order_data['shipping_address_id'] = 0;
				$order_data['shipping_firstname'] = '';
				$order_data['shipping_lastname'] = '';
				$order_data['shipping_company'] = '';
				$order_data['shipping_address_1'] = '';
				$order_data['shipping_address_2'] = '';
				$order_data['shipping_city'] = '';
				$order_data['shipping_postcode'] = '';
				$order_data['shipping_zone'] = '';
				$order_data['shipping_zone_id'] = 0;
				$order_data['shipping_country'] = '';
				$order_data['shipping_country_id'] = 0;
				$order_data['shipping_address_format'] = '';
				$order_data['shipping_custom_field'] = [];

				$order_data['shipping_method'] = [];
			}

			$points = 0;

			// Products
			$order_data['products'] = [];

			foreach ($this->cart->getProducts() as $product) {
				$option_data = [];

				foreach ($product['option'] as $option) {
					$option_data[] = [
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id'               => $option['option_id'],
						'option_value_id'         => $option['option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					];
				}

				$subscription_data = [];

				if ($product['subscription']) {
					$subscription_data = [
						'subscription_plan_id' => $product['subscription']['subscription_plan_id'],
						'name'                 => $product['subscription']['name'],
						'trial_frequency'      => $product['subscription']['trial_frequency'],
						'trial_cycle'          => $product['subscription']['trial_cycle'],
						'trial_duration'       => $product['subscription']['trial_duration'],
						'trial_remaining'      => $product['subscription']['trial_remaining'],
						'trial_status'         => $product['subscription']['trial_status'],
						'frequency'            => $product['subscription']['frequency'],
						'cycle'                => $product['subscription']['cycle'],
						'duration'             => $product['subscription']['duration']
					];
				}

				$order_data['products'][] = [
					'product_id'   => $product['product_id'],
					'master_id'    => $product['master_id'],
					'name'         => $product['name'],
					'model'        => $product['model'],
					'option'       => $option_data,
					'subscription' => $subscription_data,
					'download'     => $product['download'],
					'quantity'     => $product['quantity'],
					'subtract'     => $product['subtract'],
					'price'        => $product['price'],
					'total'        => $product['total'],
					'tax'          => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'       => $product['reward']
				];

				$points += $product['reward'];
			}

			// Gift Voucher
			$order_data['vouchers'] = [];

			if (!empty($this->session->data['vouchers'])) {
				$order_data['vouchers'] = $this->session->data['vouchers'];
			}

			if (isset($this->session->data['comment'])) {
				$order_data['comment'] = $this->session->data['comment'];
			} else {
				$order_data['comment'] = '';
			}

			// Order Totals
			$totals = [];
			$taxes = $this->cart->getTaxes();
			$total = 0;

			$this->load->model('checkout/cart');

			($this->model_checkout_cart->getTotals)($totals, $taxes, $total);

			$total_data = [
				'totals' => $totals,
				'taxes'  => $taxes,
				'total'  => $total
			];

			$order_data = array_merge($order_data, $total_data);

			$order_data['affiliate_id'] = 0;
			$order_data['commission'] = 0;
			$order_data['marketing_id'] = 0;
			$order_data['tracking'] = '';

			if (isset($this->session->data['affiliate_id'])) {
				$subtotal = $this->cart->getSubTotal();

				// Affiliate
				$this->load->model('account/affiliate');

				$affiliate_info = $this->model_account_affiliate->getAffiliate($this->session->data['affiliate_id']);

				if ($affiliate_info) {
					$order_data['affiliate_id'] = $affiliate_info['customer_id'];
					$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
					$order_data['tracking'] = $affiliate_info['tracking'];
				}
			}

			// We use session to store language code for API access
			$order_data['language_id'] = $this->config->get('config_language_id');
			$order_data['language_code'] = $this->config->get('config_language');

			$order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
			$order_data['currency_code'] = $this->session->data['currency'];
			$order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);

			$order_data['ip'] = oc_get_ip();

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

			if (!isset($this->session->data['order_id'])) {
				$this->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);
			} else {
				$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

				if ($order_info) {
					$this->model_checkout_order->editOrder($this->session->data['order_id'], $order_data);
				}
			}

			$json['order_id'] = $this->session->data['order_id'];

			// Set the order history
			if (isset($this->request->post['order_status_id'])) {
				$order_status_id = (int)$this->request->post['order_status_id'];
			} else {
				$order_status_id = (int)$this->config->get('config_order_status_id');
			}

			$this->model_checkout_order->addHistory($json['order_id'], $order_status_id);

			$json['success'] = $this->language->get('text_success');

			$json['points'] = $points;

			if (isset($order_data['affiliate_id'])) {
				$json['commission'] = $this->currency->format($order_data['commission'], $this->config->get('config_currency'));
			}
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
		$this->load->language('api/sale/order');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (isset($this->request->get['order_id'])) {
			$selected[] = (int)$this->request->get['order_id'];
		}

		$this->load->model('checkout/order');

		foreach ($selected as $order_id) {
			$order_info = $this->model_checkout_order->getOrder($order_id);

			if ($order_info) {
				$this->model_checkout_order->deleteOrder($order_id);
			}
		}

		$json['success'] = $this->language->get('text_success');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Add History
	 *
	 * @return void
	 */
	public function addHistory(): void {
		$this->load->language('api/sale/order');

		$json = [];

		// Add keys for missing post vars
		$keys = [
			'order_id',
			'order_status_id',
			'comment',
			'notify',
			'override'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder((int)$this->request->post['order_id']);

		if (!$order_info) {
			$json['error'] = $this->language->get('error_order');
		}

		if (!$json) {
			$this->model_checkout_order->addHistory((int)$this->request->post['order_id'], (int)$this->request->post['order_status_id'], (string)$this->request->post['comment'], (bool)$this->request->post['notify'], (bool)$this->request->post['override']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
