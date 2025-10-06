<?php
namespace Opencart\Catalog\Controller\Api;
/**
 * Class Order
 *
 * Order API
 *
 * @package Opencart\Catalog\Controller\Api
 */
class Order extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('api/order');

		if (isset($this->request->get['call'])) {
			$call = $this->request->get['call'];
		} else {
			$call = '';
		}

		// Allowed calls
		switch ($call) {
			case 'customer':
				$output = $this->setCustomer();
				break;
			case 'cart':
				$output = $this->getCart();
				break;
			case 'product_add':
				$output = $this->addProduct();
				break;
			case 'payment_address':
				$output = $this->setPaymentAddress();
				break;
			case 'shipping_address':
				$output = $this->setShippingAddress();
				break;
			case 'shipping_method':
				$output = $this->setShippingMethod();
				break;
			case 'shipping_methods':
				$output = $this->getShippingMethods();
				break;
			case 'payment_method':
				$output = $this->setPaymentMethod();
				break;
			case 'payment_methods':
				$output = $this->getPaymentMethods();
				break;
			case 'extension':
				$output = $this->extension();
				break;
			case 'affiliate':
				$output = $this->setAffiliate();
				break;
			case 'confirm':
				$output = $this->confirm();
				break;
			case 'history_add':
				$output = $this->addHistory();
				break;
			default:
				$output = ['error' => $this->language->get('error_call')]; // JSON error message if call not found
				break;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($output));
	}

	/**
	 * Set customer
	 *
	 * @return array<string, mixed>
	 */
	protected function setCustomer(): array {
		return $this->load->controller('api/customer');
	}

	/**
	 * Set Payment Address
	 *
	 * @return array<string, mixed>
	 */
	protected function setPaymentAddress(): array {
		return $this->load->controller('api/payment_address');
	}

	/**
	 * Set Shipping Address
	 *
	 * @return array<string, mixed>
	 */
	protected function setShippingAddress(): array {
		$output = $this->load->controller('api/cart');

		if (isset($output['error'])) {
			return $output;
		}

		return $this->load->controller('api/shipping_address');
	}

	/**
	 * Get Shipping Methods
	 *
	 * @return array<string, mixed>
	 */
	protected function getShippingMethods(): array {
		$this->load->controller('api/customer');

		$output = $this->load->controller('api/cart');

		if (isset($output['error'])) {
			return $output;
		}

		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');

		return $this->load->controller('api/shipping_method.getShippingMethods');
	}

	/**
	 * Set Shipping Method
	 *
	 * @return array<string, mixed>
	 */
	protected function setShippingMethod(): array {
		$this->load->controller('api/customer');

		$output = $this->load->controller('api/cart');

		if (isset($output['error'])) {
			return $output;
		}

		$this->load->controller('api/shipping_address');
		$this->load->controller('api/payment_address');

		$output = $this->load->controller('api/shipping_method');

		// Extensions
		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
		}

		$output['products'] = $this->load->controller('api/cart.getProducts');
		$output['totals'] = $this->load->controller('api/cart.getTotals');
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	/**
	 * Get Payment Methods
	 *
	 * @return array<string, mixed>
	 */
	protected function getPaymentMethods(): array {
		$this->load->controller('api/customer');

		$output = $this->load->controller('api/cart');

		if (isset($output['error'])) {
			return $output;
		}

		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/shipping_method');

		return $this->load->controller('api/payment_method.getPaymentMethods');
	}

	/**
	 * Set Payment Method
	 *
	 * @return array<string, mixed>
	 */
	protected function setPaymentMethod(): array {
		$this->load->controller('api/customer');

		$output = $this->load->controller('api/cart');

		if (isset($output['error'])) {
			return $output;
		}

		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/shipping_method');

		$output = $this->load->controller('api/payment_method');

		// Extensions
		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
		}

		$output['products'] = $this->load->controller('api/cart.getProducts');
		$output['totals'] = $this->load->controller('api/cart.getTotals');
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	/**
	 * Extension
	 *
	 * @return array<string, mixed>
	 */
	protected function extension(): array {
		$this->load->controller('api/customer');
		$this->load->controller('api/cart');
		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/shipping_method');
		$this->load->controller('api/payment_method');
		$this->load->controller('api/affiliate');

		if (isset($this->request->get['code'])) {
			$code = (string)$this->request->get['code'];
		} else {
			$code = '';
		}

		$output = [];

		// Extensions
		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$result = $this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);

			if (!$result instanceof \Exception && $extension['code'] == $code) {
				$output = $result;
			}
		}

		$output['products'] = $this->load->controller('api/cart.getProducts');
		$output['totals'] = $this->load->controller('api/cart.getTotals');
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	/**
	 * Set Affiliate
	 *
	 * @return array<string, mixed>
	 */
	protected function setAffiliate(): array {
		return $this->load->controller('api/affiliate');
	}

	/**
	 * Get Cart
	 *
	 * @return array<string, mixed>
	 */
	protected function getCart(): array {
		$this->load->controller('api/customer');

		// If any errors at the cart level such as products don't exist then we want to return the error
		$output = $this->load->controller('api/cart');

		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');

		// Extensions
		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
		}

		$this->load->controller('api/shipping_method');
		$this->load->controller('api/payment_method');

		$output['products'] = $this->load->controller('api/cart.getProducts');
		$output['totals'] = $this->load->controller('api/cart.getTotals');
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	/**
	 * Add Product
	 *
	 * @return array<string, mixed>
	 */
	protected function addProduct(): array {
		$this->load->controller('api/customer');

		$output = $this->load->controller('api/cart');

		if (isset($output['error'])) {
			return $output;
		}

		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');

		$output = $this->load->controller('api/cart.addProduct');

		$this->load->controller('api/shipping_method');
		$this->load->controller('api/payment_method');

		// Extensions
		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
		}

		$output['products'] = $this->load->controller('api/cart.getProducts');
		$output['totals'] = $this->load->controller('api/cart.getTotals');
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	/**
	 * Confirm Order
	 *
	 * @return array<string, mixed>
	 */
	protected function confirm(): array {
		$this->load->controller('api/customer');

		// Validate cart has products and has stock.
		$output = $this->load->controller('api/cart');

		if (isset($output['error'])) {
			return $output;
		}

		$required = [
			'order_id'        => 0,
			'affiliate_id'    => 0,
			'comment'         => '',
			'order_status_id' => 0
		];

		$post_info = $this->request->post + $required;

		$this->load->controller('api/payment_address');
		$this->load->controller('api/shipping_address');
		$this->load->controller('api/shipping_method');
		$this->load->controller('api/payment_method');
		$this->load->controller('api/affiliate');

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

		// 4. Validate payment address, if required
		if ($this->config->get('config_checkout_payment_address') && !isset($this->session->data['payment_address'])) {
			$output['error']['payment_address'] = $this->language->get('error_payment_address');
		}

		// 5. Validate shipping address and method, if required
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
		if (isset($this->request->post['affiliate_id']) && !isset($this->session->data['affiliate_id'])) {
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
			$order_data['subscription_id'] = 0;

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
			$order_data['custom_field'] = $this->session->data['customer']['custom_field'] ?? [];

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

			if (isset($post_info['comment'])) {
				$order_data['comment'] = (string)$post_info['comment'];
			} else {
				$order_data['comment'] = '';
			}

			// Order Totals
			$totals = [];
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Cart
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

			if (isset($post_info['order_id'])) {
				$order_id = (int)$post_info['order_id'];
			} else {
				$order_id = 0;
			}

			// Order
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
			if (isset($post_info['order_status_id'])) {
				$order_status_id = (int)$post_info['order_status_id'];
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

		$output['products'] = $this->load->controller('api/cart.getProducts');
		$output['totals'] = $this->load->controller('api/cart.getTotals');
		$output['shipping_required'] = $this->cart->hasShipping();

		return $output;
	}

	/**
	 * Add History
	 *
	 * @return array<string, mixed>
	 */
	protected function addHistory(): array {
		$this->load->language('api/order');

		$output = [];

		// Add keys for missing post vars
		$required = [
			'order_id'        => 0,
			'order_status_id' => 0,
			'comment'         => '',
			'notify'          => 0,
			'override'        => 0
		];

		$post_info = $this->request->post + $required;

		// Order
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder((int)$post_info['order_id']);

		if (!$order_info) {
			$output['error'] = $this->language->get('error_order');
		}

		if (!$output) {
			$this->model_checkout_order->addHistory((int)$post_info['order_id'], (int)$post_info['order_status_id'], (string)$post_info['comment'], (bool)$post_info['notify'], (bool)$post_info['override']);

			$output['success'] = $this->language->get('text_success');
		}

		return $output;
	}
}
