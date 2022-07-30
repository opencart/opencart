<?php
namespace Opencart\Catalog\Controller\Api\Sale;
use \Opencart\System\Helper as Helper;
class Order extends \Opencart\System\Engine\Controller {
	/*
	 * Loads order info
	 * */
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
			if ($this->config->get('config_checkout_address')) {
				$this->session->data['payment_address'] = [
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
			}

			if ($order_info['payment_code']) {
				$this->session->data['payment_method'] = $order_info['payment_code'];
			}

			if ($order_info['shipping_code']) {
				$this->session->data['shipping_address'] = [
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

				$this->session->data['shipping_method'] = $order_info['shipping_code'];
			}

			$this->session->data['comment'] = $order_info['comment'];

			if ($order_info['language_code']) {
				$this->session->data['language'] = $order_info['language_code'];
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
						$option_data[$option['product_option_id']][] = $option['value'];
					}
				}

				$this->cart->add($product['product_id'], $product['quantity'], $option_data);
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
				$start = strpos($order_total['title'], '(') + 1;
				$end = strrpos($order_total['title'], ')');

				if ($start && $end) {
					$this->session->data[$order_total['code']] = substr($order_total['title'], $start, $end - $start);
				}
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

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
		if ($this->config->get('config_checkout_address') && !isset($this->session->data['payment_address'])) {
			$json['error']['payment_address'] = $this->language->get('error_payment_address');
		}

		// Shipping
		if ($this->cart->hasShipping()) {
			// Shipping Address
			if (!isset($this->session->data['shipping_address'])) {
				$json['error']['shipping_address'] = $this->language->get('error_shipping_address');
			}

			// Validate shipping method
			if (isset($this->session->data['shipping_method']) && isset($this->session->data['shipping_methods'])) {
				$shipping = explode('.', $this->session->data['shipping_method']);

				if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
					$json['error']['shipping_method'] = $this->language->get('error_shipping_method');
				}
			} else {
				$json['error']['shipping_method'] = $this->language->get('error_shipping_method');
			}
		} else {
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		// Payment Method
		if (!isset($this->session->data['payment_method']) || !isset($this->session->data['payment_methods']) || !isset($this->session->data['payment_methods'][$this->session->data['payment_method']])) {
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
			if ($this->config->get('config_checkout_address')) {
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
				$order_data['payment_custom_field'] = isset($this->session->data['payment_address']['custom_field']) ? $this->session->data['payment_address']['custom_field'] : [];
			} else {
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

			$payment_method_info = $this->session->data['payment_methods'][$this->session->data['payment_method']];

			$order_data['payment_method'] = $payment_method_info['title'];
			$order_data['payment_code'] = $payment_method_info['code'];

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
				$order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
				$order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
				$order_data['shipping_custom_field'] = isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : [];
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
			}

			if (isset($this->session->data['shipping_method'])) {
				$shipping = explode('.', $this->session->data['shipping_method']);

				$shipping_method_info = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

				$order_data['shipping_method'] = $shipping_method_info['title'];
				$order_data['shipping_code'] = $shipping_method_info['code'];
			} else {
				$order_data['shipping_method'] = '';
				$order_data['shipping_code'] = '';
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

				$order_data['products'][] = [
					'product_id'   => $product['product_id'],
					'master_id'    => $product['master_id'],
					'name'         => $product['name'],
					'model'        => $product['model'],
					'option'       => $option_data,
					'subscription' => $product['subscription'],
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
						'code'             => Helper\General\token(10),
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
				$order_status_id = $this->config->get('config_order_status_id');
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

	public function delete(): void {
		$this->load->language('api/sale/order');

		$json = [];

		$selected = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		}

		if (isset($this->request->get['order_id'])) {
			$selected[] = (int)$this->request->get['order_id'];
		}

		foreach ($selected as $order_id) {
			$this->load->model('checkout/order');

			$order_info = $this->model_checkout_order->getOrder($order_id);

			if ($order_info) {
				$this->model_checkout_order->deleteOrder($order_id);
			}
		}

		$json['success'] = $this->language->get('text_success');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

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
