<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Subscription
 *
 * Can be loaded using $this->load->controller('cron/subscription');
 *
 * @package Opencart\Catalog\Controller\Task\Admin
 */
class Subscription extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate subscription task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/subscription');

		// Check if there is an order, the order status is complete and subscription status is active
		$filter_data = [
			'filter_date_next'              => date('Y-m-d H:i:s'),
			'filter_subscription_status_id' => $this->config->get('config_subscription_active_status_id')
		];

		// Subscriptions
		$this->load->model('checkout/subscription');

		$results = $this->model_checkout_subscription->getSubscriptions($filter_data);

		foreach ($results as $result) {
			if (($result['trial_status'] && $result['trial_remaining']) || (!$result['duration'] && $result['remaining'])) {
				$task_data = [
					'code'   => 'subscription',
					'action' => 'task/catalog/subscription.confirm',
					'args'   => ['subscription_id' => $result['subscription_id']]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		return ['success' => $this->language->get('text_success')];
	}

	public function confirm(array $args = []): array {
		$this->load->language('task/admin/subscription');

		// Subscription
		$this->load->model('checkout/subscription');

		$subscription_info = $this->model_checkout_subscription->getSubscription((int)$args['subscription_id']);

		if (!$subscription_info) {
			return ['error' => $this->language->get('error_subscription')];
		}

		// Store
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore($subscription_info['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguageByCode($subscription_info['language']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		// Currency
		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrencyByCode($subscription_info['currency_code']);

		if (!$currency_info) {
			return ['error' => $this->language->get('error_currency')];
		}

		// Customer
		$this->load->model('account/customer');

		$customer_info = $this->model_account_customer->getCustomer($subscription_info['customer_id']);

		if (!$customer_info || !$this->customer->login($customer_info['email'], '', true)) {
			return ['error' => $this->language->get('error_customer')];
		}

		// Payment Address
		if ($subscription_info['payment_address_id']) {
			$this->load->model('account/address');

			$payment_address_info = $this->model_account_address->getAddress($customer_info['customer_id'], $subscription_info['payment_address_id']);

			if (!$payment_address_info) {
				return ['error' => $this->language->get('error_payment_address')];
			}
		}

		// Shipping Address
		if ($subscription_info['shipping_address_id']) {
			$this->load->model('account/address');

			$shipping_address_info = $this->model_account_address->getAddress($customer_info['customer_id'], $subscription_info['shipping_address_id']);

			if (!$shipping_address_info) {
				return ['error' => $this->language->get('error_shipping_address')];
			}
		}

		// Shipping Method
		$keys = [
			'name',
			'code',
			'cost',
			'tax_class_id'
		];

		foreach ($keys as $key) {
			if (!isset($subscription_info['shipping_method'][$key])) {
				return ['error' => $this->language->get('error_shipping_method')];
			}
		}

		// Payment Method
		$keys = [
			'name',
			'code'
		];

		foreach ($keys as $key) {
			if (!isset($subscription_info['payment_method'][$key])) {
				return ['error' => $this->language->get('error_payment_method')];
			}
		}

		// Subscription Plan
		$this->load->model('catalog/subscription_plan');

		$subscription_plan_info = $this->model_catalog_subscription_plan->getSubscriptionPlan($subscription_info['subscription_plan_id']);

		if (!$subscription_plan_info) {
			return ['error' => $this->language->get('error_subscription_plan')];
		}




		// Create new instance of a store
		$store = $this->model_setting_store->createStoreInstance($subscription_info['store_id'], $subscription_info['language'], $subscription_info['currency_code']);

		// Language
		$store->request->get['language'] = $language_info['code'];

		$store->config->set('config_language_id', $language_info['language_id']);
		$store->config->set('config_language', $language_info['code']);

		// Currency
		$store->session->data['currency'] = $currency_info['code'];

		// Customer
		$store->session->data['customer'] = $customer_info;

		// Products
		$product_data = [];/**/

		$store->load->model('checkout/subscription');

		$products = $store->model_checkout_subscription->getProducts($subscription_info['subscription_id']);

		foreach ($products as $product) {
			$product_info = $store->model_catalog_product->getProduct($product['product_id']);

			if (!$product_info) {
				return ['error' => sprintf($this->language->get('error_product'), $product['name'])];
			}



			$option_data = [];

			$options = $store->model_checkout_subscription->getOptions($subscription_info['subscription_id'], $product['subscription_product_id']);

			foreach ($options as $option) {
				$option_info = $store->model_catalog_product->getOption($product_info['product_id'], $option['product_option_id']);

				if (!$option_info) {
					return ['error' => sprintf($this->language->get('error_option'), $product['name'], $option['name'], $option['product_option_name'])];
				}

				if ($option_info['required'] && !isset($option_data[$option['product_option_id']])) {
					return ['error' => sprintf($this->language->get('error_option'), $option['name'])];
				}

				if ($option['type'] == 'select' || $option['type'] == 'radio') {
					$option_data[$option['product_option_id']] = $option_info['product_option_value_id'];
				} elseif ($option['type'] == 'checkbox') {
					$option_data[$option['product_option_id']][] = $option_info['product_option_value_id'];
				} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$option_data[$option['product_option_id']] = $option_info['value'];
				}
			}

			$price = $product['price'];

			if ($product['trial_status']) {
				$price = $product['trial_price'];
			}

			$store->cart->add($product['product_id'], $product['quantity'], $option_data, $subscription_plan_info['subscription_plan_id'], ['price' => $price]);

		}

		// Payment Address
		$store->session->data['payment_address'] = $payment_address_info;

		// Payment Method
		$store->session->data['payment_method'] = $subscription_info['payment_method'];

		if ($subscription_info['shipping_address_id']) {
			// Shipping Address
			$store->session->data['shipping_address'] = $shipping_address_info;

			// Shipping Method
			$store->session->data['shipping_method'] = $subscription_info['shipping_method'];
		}

		$store->load->model('checkout/cart');

		$products = $store->model_checkout_cart->getProducts();

		foreach ($products as $product) {
			$order_data['products'][] = [
				'subscription' => [],
				'tax'          => $store->tax->getTax($price, $product['tax_class_id'])
			] + $product;
		}

		$store->load->model('catalog/product');



		$this->load->model('checkout/order');
		// Order Totals
		$totals = [];
		$taxes = $store->cart->getTaxes();
		$total = 0;

		($store->model_checkout_cart->getTotals)($totals, $taxes, $total);

		$total_data = [
			'totals' => $totals,
			'taxes'  => $taxes,
			'total'  => $total
		];


			// Validate if payment extension installed
		$store->load->model('setting/extension');

		$extension_info = $store->model_setting_extension->getExtensionByCode('payment', strstr($subscription_info['payment_method']['code'], '.', true));

		if (!$extension_info) {
			return ['error' => $this->language->get('error_extension')];
		}

		// Validate if payment extension installed

		$store->load->controller('extension/' . $extension_info['extension'] . '/cron/' . $extension_info['code']);

		// Add subscription history failed if payment method for cron didn't exist
		$store->model_checkout_subscription->addHistory($subscription_info['subscription_id'], $this->config->get('config_subscription_failed_status_id'), $this->language->get('text_log'));

		// Log errors
		$store->model_checkout_subscription->addLog($subscription_info['subscription_id'], $key, $value);


		// 7. Clean up data by clearing cart.
		$store->cart->clear();

		// 8. Deleting the current session so we are not creating infinite sessions.
		$store->session->destroy();


		// Subscription
		$order_data = [];

		$order_data['subscription_id'] = $subscription_info['subscription_id'];
		$order_data['invoice_prefix'] = $store->config->get('config_invoice_prefix');

		// Store Details
		$order_data['store_id'] = $store_info['store_id'];
		$order_data['store_name'] = $store_info['name'];
		$order_data['store_url'] = $store_info['url'];

		// Customer Details
		$order_data['customer_id'] = $customer_info['customer_id'];
		$order_data['customer_group_id'] = $customer_info['customer_group_id'];
		$order_data['firstname'] = $customer_info['firstname'];
		$order_data['lastname'] = $customer_info['lastname'];
		$order_data['email'] = $customer_info['email'];
		$order_data['telephone'] = $customer_info['telephone'];
		$order_data['custom_field'] = $customer_info['custom_field'];

		// Payment Details
		if ($payment_address_info) {
			$order_data['payment_address_id'] = $payment_address_info['address_id'];
			$order_data['payment_firstname'] = $payment_address_info['firstname'];
			$order_data['payment_lastname'] = $payment_address_info['lastname'];
			$order_data['payment_company'] = $payment_address_info['company'];
			$order_data['payment_address_1'] = $payment_address_info['address_1'];
			$order_data['payment_address_2'] = $payment_address_info['address_2'];
			$order_data['payment_city'] = $payment_address_info['city'];
			$order_data['payment_postcode'] = $payment_address_info['postcode'];
			$order_data['payment_zone'] = $payment_address_info['zone'];
			$order_data['payment_zone_id'] = $payment_address_info['zone_id'];
			$order_data['payment_country'] = $payment_address_info['country'];
			$order_data['payment_country_id'] = $payment_address_info['country_id'];
			$order_data['payment_address_format'] = $payment_address_info['address_format'];
			$order_data['payment_custom_field'] = $payment_address_info['custom_field'];
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

		$order_data['payment_method'] = $subscription_info['payment_method'];

		// Shipping Details
		if ($shipping_address_info) {
			$order_data['shipping_address_id'] = $shipping_address_info['address_id'];
			$order_data['shipping_firstname'] = $shipping_address_info['firstname'];
			$order_data['shipping_lastname'] = $shipping_address_info['lastname'];
			$order_data['shipping_company'] = $shipping_address_info['company'];
			$order_data['shipping_address_1'] = $shipping_address_info['address_1'];
			$order_data['shipping_address_2'] = $shipping_address_info['address_2'];
			$order_data['shipping_city'] = $shipping_address_info['city'];
			$order_data['shipping_postcode'] = $shipping_address_info['postcode'];
			$order_data['shipping_zone'] = $shipping_address_info['zone'];
			$order_data['shipping_zone_id'] = $shipping_address_info['zone_id'];
			$order_data['shipping_country'] = $shipping_address_info['country'];
			$order_data['shipping_country_id'] = $shipping_address_info['country_id'];
			$order_data['shipping_address_format'] = $shipping_address_info['address_format'];
			$order_data['shipping_custom_field'] = $shipping_address_info['custom_field'];

			$order_data['shipping_method'] = $subscription_info['shipping_method'];
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

		// Products
		$order_data['products'] = [];

		$store->load->model('checkout/cart');

		$products = $store->model_checkout_cart->getProducts();

		foreach ($products as $product) {
			$order_data['products'][] = [
				'subscription' => [],
				'tax'          => $store->tax->getTax($price, $product['tax_class_id'])
			] + $product;
		}

		// Order Totals
		$totals = [];
		$taxes = $store->cart->getTaxes();
		$total = 0;

		($store->model_checkout_cart->getTotals)($totals, $taxes, $total);

		$total_data = [
			'totals' => $totals,
			'taxes'  => $taxes,
			'total'  => $total
		];

		$order_data += $total_data;

		// Comment
		$order_data['comment'] = $subscription_info['comment'];

		$order_data['affiliate_id'] = 0;
		$order_data['commission'] = 0;
		$order_data['marketing_id'] = 0;
		$order_data['tracking'] = '';

		// Language
		$order_data['language_id'] = $language_info['language_id'];
		$order_data['language_code'] = $language_info['code'];

		// Currency
		$order_data['currency_id'] = $currency_info['currency_id'];
		$order_data['currency_code'] = $currency_info['code'];
		$order_data['currency_value'] = $subscription_info['currency_value'];

		// Order
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($subscription_info['order_id']);

		if ($order_info) {
			$order_data['ip'] = $order_info['ip'];
			$order_data['forwarded_ip'] = $order_info['forwarded_ip'];
			$order_data['user_agent'] = $order_info['user_agent'];
			$order_data['accept_language'] = $order_info['accept_language'];
		} else {
			$order_data['ip'] = '';
			$order_data['forwarded_ip'] = '';
			$order_data['user_agent'] = '';
			$order_data['accept_language'] = '';
		}

		$store->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);

		return ['success' => $this->language->get('text_success')];
	}
}
