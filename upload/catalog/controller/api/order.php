<?php
namespace Opencart\catalog\controller\api;
/**
 * Class Order
 *
 * @package Opencart\Catalog\Controller\Api
 */
class Order extends \Opencart\System\Engine\Controller {
	/**
	 * Confirm
	 *
	 * @return void
	 */
	public function confirm(): void {
		$this->load->language('api/order');

		$json = [];

		$this->load->controller('api/customer');
		$this->load->controller('api/cart.add');
		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/shipping_method.save');
		$this->load->controller('api/payment_method.save');

		// 1. Validate customer data exists
		if (!isset($this->session->data['customer'])) {
			$json['error']['customer'] = $this->language->get('error_customer');
		}

		// 2. Validate cart has products.
		if (!$this->cart->hasProducts()) {
			$json['error']['product'] = $this->language->get('error_product');
		}

		// 3. Validate cart has products and has stock
		if ((!$this->cart->hasStock() && !$this->config->get('config_stock_checkout')) || !$this->cart->hasMinimum()) {
			$json['error']['stock'] = $this->language->get('error_stock');
		}

		// 4. Validate payment address if required
		if ($this->config->get('config_checkout_payment_address') && !isset($this->session->data['payment_address'])) {
			$json['error']['payment_address'] = $this->language->get('error_payment_address');
		}

		// 5. Validate shipping address and method if required
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
		}

		// 6. Validate payment Method
		if (!isset($this->session->data['payment_method'])) {
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
			if (isset($this->session->data['payment_address'])) {
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
		$this->load->language('api/order');

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
