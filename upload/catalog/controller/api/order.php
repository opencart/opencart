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
	 * @return array
	 */
	public function index(): array {
		$this->load->language('api/order');

		$output = [];

		// 1. Validate customer data exists
		if (!isset($this->session->data['customer'])) {
			$output['error']['customer'] = $this->language->get('error_customer');
		}

		// 2. Validate cart has products.
		if (!$this->cart->hasProducts()) {
			$output['error']['product'] = $this->language->get('error_product');
		}

		// 3. Validate cart has products and has stock
		if ((!$this->cart->hasStock() && !$this->config->get('config_stock_checkout')) || !$this->cart->hasMinimum()) {
			$output['error']['product'] = $this->language->get('error_stock');
		}

		// 4. Validate payment address if required
		if ($this->config->get('config_checkout_payment_address') && !isset($this->session->data['payment_address'])) {
			$output['error']['payment_address'] = $this->language->get('error_payment_address');
		}

		// 5. Validate shipping address and method if required
		if ($this->cart->hasShipping()) {
			// Shipping Address
			if (!isset($this->session->data['shipping_address'])) {
				$output['error']['shipping_address'] = $this->language->get('error_shipping_address');
			}

			// Validate shipping method
			if (!isset($this->session->data['shipping_method'])) {
				$output['error']['shipping_method'] = $this->language->get('error_shipping_method');
			}
		} else {
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
		}

		// 6. Validate payment method
		if (!isset($this->session->data['payment_method'])) {
			$output['error']['payment_method'] = $this->language->get('error_payment_method');
		}

		// 7. Validate affiliate if set
		if (isset($thid->request->post['affiliate_id']) && !isset($this->session->data['affiliate_id'])) {
			$output['error']['affiliate'] = $this->language->get('error_affiliate');
		}

		// 8. Validate coupons, rewards
		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$result = $this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);

			if (!$result instanceof \Exception && isset($result['error'])) {
				$this->load->language('extension/' . $extension['extension'] . '/api/' . $extension['code'], 'total');

				$output['error'][$extension['code']] = $this->language->get('total_error_confirm');
			}
		}

		if (!$output) {
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

			$products = $this->cart->getProducts();

			foreach ($products as $product) {
				$subscription_data = [];

				if ($product['subscription']) {
					$subscription_data = [
						'trial_tax' => $this->tax->getTax($product['subscription']['trial_price'], $product['tax_class_id']),
						'tax'       => $this->tax->getTax($product['subscription']['price'], $product['tax_class_id'])
					] + $product['subscription'];
				}

				$order_data['products'][] = [
					'subscription' => $subscription_data,
					'tax'          => $this->tax->getTax($product['price'], $product['tax_class_id'])
				] + $product;

				$points += $product['reward'];
			}

			if (isset($this->request->post['comment'])) {
				$order_data['comment'] = (string)$this->request->post['comment'];
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

			if (isset($this->request->post['order_id'])) {
				$order_id = (int)$this->request->post['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('checkout/order');

			if (!$order_id) {
				$order_id = $this->model_checkout_order->addOrder($order_data);
			} else {
				$order_info = $this->model_checkout_order->getOrder($order_id);

				if ($order_info) {
					$this->model_checkout_order->editOrder($order_id, $order_data);
				}
			}

			$output['order_id'] = $order_id;

			// Set the order history
			if (isset($this->request->post['order_status_id'])) {
				$order_status_id = (int)$this->request->post['order_status_id'];
			} else {
				$order_status_id = (int)$this->config->get('config_order_status_id');
			}

			$this->model_checkout_order->addHistory($order_id, $order_status_id);

			$output['success'] = $this->language->get('text_success');

			$output['points'] = $points;

			if (isset($order_data['affiliate_id'])) {
				$output['commission'] = $this->currency->format($order_data['commission'], $this->config->get('config_currency'));
			}
		}

		return $output;
	}

	/**
	 * Delete
	 *
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('api/sale/order');

		$output = [];

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

		$output['success'] = $this->language->get('text_success');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($output));
	}

	/**
	 * Add History
	 *
	 * @return array
	 */
	public function addHistory(): array {
		$this->load->language('api/order');

		$output = [];

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
			$output['error'] = $this->language->get('error_order');
		}

		if (!$output) {
			$this->model_checkout_order->addHistory((int)$this->request->post['order_id'], (int)$this->request->post['order_status_id'], (string)$this->request->post['comment'], (bool)$this->request->post['notify'], (bool)$this->request->post['override']);

			$output['success'] = $this->language->get('text_success');
		}

		return $output;
	}
}
