<?php
namespace Opencart\Catalog\Controller\Cron;
class Subscription extends \Opencart\System\Engine\Controller {
	public function index(int $cron_id, string $code, string $cycle, string $date_added, string $date_modified): void {
        $this->load->language('cron/subscription');

		// Check the there is an order and the order status is complete and subscription status is active
		$filter_data = [
			'filter_date_next'              => date('Y-m-d H:i:s'),
			'filter_subscription_status_id' => $this->config->get('config_subscription_active_status_id'),
			'start'                         => 0,
			'limit'                         => 10
		];

		// Get all
		$this->load->model('checkout/subscription');
		$this->load->model('checkout/order');
		$this->load->model('catalog/product');
		$this->load->model('setting/store');
		$this->load->model('account/customer');
		$this->load->model('account/address');

        $results = $this->model_checkout_subscription->getSubscriptions($filter_data);

		foreach ($results as $result) {
			$order_info = $this->model_checkout_order->getOrder($result['order_id']);

			// Check the there is an order and the order status is complete and subscription status is active
			if ($order_info && in_array($order_info['order_status_id'], (array)$this->config->get('config_complete_status'))) {
				$error = '';

				// Customer
				$customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

				if (!$customer_info) {
					$error = $this->language->get('error_customer');
				}

				// Product
				$product_info = $this->model_checkout_order->getProduct($result['order_id']);

				if (!$product_info) {
					$error = $this->language->get('error_product');
				}

				// 1. Add product
				if (!$error) {
					$option_data = [];

					$order_options = $this->model_account_order->getOptions($result['order_id'], $result['order_product_id']);

					foreach ($order_options as $order_option) {
						if ($order_option['type'] == 'select' || $order_option['type'] == 'radio' || $order_option['type'] == 'image') {
							$option_data[$order_option['product_option_id']] = $order_option['product_option_value_id'];
						} elseif ($order_option['type'] == 'checkbox') {
							$option_data[$order_option['product_option_id']][] = $order_option['product_option_value_id'];
						} elseif ($order_option['type'] == 'text' || $order_option['type'] == 'textarea' || $order_option['type'] == 'date' || $order_option['type'] == 'datetime' || $order_option['type'] == 'time') {
							$option_data[$order_option['product_option_id']] = $order_option['value'];
						} elseif ($order_option['type'] == 'file') {
							$option_data[$order_option['product_option_id']] = $order_option['value'];
						}
					}

					$store = $this->model_setting_store->createStoreInstance($order_info['store_id'], $order_info['language_code']);
					$store->cart->add($result['product_id'], $result['quantity'], $option_data);
				}

				// Payment Address
				if ($this->config->get('config_checkout_payment_address')) {
					$payment_address_info = $this->model_account_address->getAddress($order_info['customer_id'], $result['payment_address_id']);

					if (!$payment_address_info) {
						$error = $this->language->get('error_payment_address');
					}
				} else {
					$payment_address_info = [];
				}

				// Shipping Address
				if ($store->cart->hasShipping()) {
					// Validate shipping address
					$shipping_address_info = $this->model_account_address->getAddress($order_info['customer_id'], $order_info['shipping_address_id']);

					if (!$shipping_address_info) {
						$error = $this->language->get('error_shipping_address');
					}
				}

				// Shipping
				if ($store->cart->hasShipping()) {
					// Shipping Methods
					$this->load->model('checkout/shipping_method');

					$shipping_methods = $this->model_checkout_shipping_method->getMethods($shipping_address_info);


					// Validate shipping address
					if (!isset($this->session->data['shipping_address'])) {
						$status = false;
					}

					// Validate shipping method
					if (isset($this->session->data['shipping_method']) && isset($this->session->data['shipping_methods'])) {
						$shipping = explode('.', $this->session->data['shipping_method']);

						if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
							$status = false;
						}
					} else {
						$status = false;
					}
				} else {
					unset($this->session->data['shipping_address']);
					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
				}


				// Payment Methods
				$this->load->model('checkout/payment_method');

				$payment_methods = $this->model_checkout_payment_method->getMethods($payment_address_info);

				if ($payment_methods) {
					// Store payment methods in session
					$this->session->data['payment_methods'] = $payment_methods;
				}

				// Validate payment methods
				if ($order_info['payment_code'] && $payment_methods) {
					$payment = explode('.', $order_info['payment_code']);

					if (!isset($payment[0]) || !isset($payment[1]) || !isset($payment_methods[$payment[0]]['option'][$payment[1]])) {
						$json['error'] = $this->language->get('error_payment_method');
					}
				} else {
					$json['error'] = $this->language->get('error_payment_method');
				}










				// Order Totals
				$totals = [];
				$taxes = $this->cart->getTaxes();
				$total = 0;

				$this->load->model('checkout/cart');

				($this->model_checkout_cart->getTotals)($totals, $taxes, $total);

				$data['totals'] = [];

				foreach ($totals as $total) {
					$data['totals'][] = [
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
					];
				}






				// Store Details
				$order_data['invoice_prefix'] = $order_info['invoice_prefix'];
				$order_data['store_id'] = $order_info['store_id'];
				$order_data['store_name'] = $this->config->get('config_name');
				$order_data['store_url'] = $this->config->get('config_url');

				$customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

				// Customer Details
				$order_data['customer_id'] = $customer_info['customer_id'];
				$order_data['customer_group_id'] = $customer_info['customer_group_id'];
				$order_data['firstname'] = $customer_info['firstname'];
				$order_data['lastname'] = $customer_info['lastname'];
				$order_data['email'] = $customer_info['email'];
				$order_data['telephone'] = $customer_info['telephone'];
				$order_data['custom_field'] = $customer_info['custom_field'];

				// Payment Details
				$payment_info = $this->model_account_address->getAddress($customer_info['customer_id'], $order_info['payment_address_id']);

				if ($payment_info) {
					$order_data['payment_address_id'] = $payment_info['address_id'];
					$order_data['payment_firstname'] = $payment_info['firstname'];
					$order_data['payment_lastname'] = $payment_info['lastname'];
					$order_data['payment_company'] = $payment_info['company'];
					$order_data['payment_address_1'] = $payment_info['address_1'];
					$order_data['payment_address_2'] = $payment_info['address_2'];
					$order_data['payment_city'] = $payment_info['city'];
					$order_data['payment_postcode'] = $payment_info['postcode'];
					$order_data['payment_zone'] = $payment_info['zone'];
					$order_data['payment_zone_id'] = $payment_info['zone_id'];
					$order_data['payment_country'] = $payment_info['country'];
					$order_data['payment_country_id'] = $payment_info['country_id'];
					$order_data['payment_address_format'] = $payment_info['address_format'];
					$order_data['payment_custom_field'] = $payment_info['custom_field'];
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

				// Shipping Details
				$shipping_info = $this->model_account_address->getAddress($customer_info['customer_id'], $order_info['shipping_address_id']);

				if ($shipping_info) {
					$order_data['shipping_address_id'] = $shipping_info['address_id'];
					$order_data['shipping_firstname'] = $shipping_info['firstname'];
					$order_data['shipping_lastname'] = $shipping_info['lastname'];
					$order_data['shipping_company'] = $shipping_info['company'];
					$order_data['shipping_address_1'] = $shipping_info['address_1'];
					$order_data['shipping_address_2'] = $shipping_info['address_2'];
					$order_data['shipping_city'] = $shipping_info['city'];
					$order_data['shipping_postcode'] = $shipping_info['postcode'];
					$order_data['shipping_zone'] = $shipping_info['zone'];
					$order_data['shipping_zone_id'] = $shipping_info['zone_id'];
					$order_data['shipping_country'] = $shipping_info['country'];
					$order_data['shipping_country_id'] = $shipping_info['country_id'];
					$order_data['shipping_address_format'] = $shipping_info['address_format'];
					$order_data['shipping_custom_field'] = $shipping_info['custom_field'];
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
				}

				if (isset($this->session->data['shipping_method'])) {
					$shipping_method_info = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

					$order_data['shipping_method'] = $shipping_method_info['title'];
					$order_data['shipping_code'] = $shipping_method_info['code'];
				} else {
					$order_data['shipping_method'] = '';
					$order_data['shipping_code'] = '';
				}

				$order_data['payment_code'] = $this->session->data['payment_methods'][$payment[0]]['option'][$payment[1]]['code'];




				$products = $this->model_checkout_order->getProduct($result['order_id'], $result['order_product_id']);

				$order_data['options'] = $this->model_checkout_order->getOptions($result['order_id'], $result['order_product_id']);
				$order_data['subscription'] = $this->model_checkout_order->getSubscription($result['order_id'], $result['order_product_id']);
				$order_data['voucher'] = $this->model_checkout_order->getVouchers($result['order_id'], $result['order_product_id']);
				$order_data['totals'] = $this->model_checkout_order->getTotals($result['order_id'], $result['order_product_id']);


				$order_data['products'] = [];


				if ($result['trial_status'] && (!$result['trial_duration'] || $result['trial_remaining']) && ) {
					$amount = $result['trial_price'];
				} elseif (!$result['duration'] || $result['remaining']) {
					$amount = $result['price'];
				}

				$subscription_status_id = $this->config->get('config_subscription_status_id');

				// Get the payment method used by the subscription
				// Check payment status
				//$this->load->model('extension/payment/' . $payment_info['code']);


				// Transaction
				if ($this->config->get('config_subscription_active_status_id') == $subscription_status_id) {
					if ($result['trial_duration'] && $result['trial_remaining']) {
						$date_next = date('Y-m-d', strtotime('+' . $result['trial_cycle'] . ' ' . $result['trial_frequency']));
					} elseif ($result['duration'] && $result['remaining']) {
						$date_next = date('Y-m-d', strtotime('+' . $result['cycle'] . ' ' . $result['frequency']));
					}

					$filter_data = [
						'filter_date_next' => $date_next,
						'filter_subscription_status_id' => $subscription_status_id,
						'start' => 0,
						'limit' => 1
					];

					$subscriptions = $this->model_account_subscription->getSubscriptions($filter_data);

					if ($subscriptions) {
						// Only match the latest order ID of the same customer ID
						// since new subscriptions cannot be re-added with the same
						// order ID; only as a new order ID added by an extension
						foreach ($subscriptions as $subscription) {
							if ($subscription['customer_id'] == $result['customer_id'] && ($subscription['subscription_id'] != $result['subscription_id']) && ($subscription['order_id'] != $result['order_id']) && ($subscription['order_product_id'] != $result['order_product_id'])) {
								$subscription_info = $this->model_account_subscription->getSubscription($subscription['subscription_id']);

								if ($subscription_info) {
									// $this->model_account_subscription->addTransaction($subscription['subscription_id'], $subscription['order_id'], $this->language->get('text_success'), $amount, $subscription_info['type'], $subscription_info['payment_method'], $subscription_info['payment_code']);
								}
							}
						}
					}
				}
			}
		}

		// Failed if payment method does not have recurring payment method
		$subscription_status_id = $this->config->get('config_subscription_failed_status_id');

		$this->model_checkout_subscription->addHistory($result['subscription_id'], $subscription_status_id, $this->language->get('error_recurring'), true);

		$subscription_status_id = $this->config->get('config_subscription_failed_status_id');

		$this->model_checkout_subscription->addHistory($result['subscription_id'], $subscription_status_id, $this->language->get('error_extension'), true);

		$this->model_checkout_subscription->addHistory($result['subscription_id'], $subscription_status_id, sprintf($this->language->get('error_payment'), ''), true);

		// History
		if ($result['subscription_status_id'] != $subscription_status_id) {
			$this->model_checkout_subscription->addHistory($result['subscription_id'], $subscription_status_id, 'payment extension ' . $result['payment_code'] . ' could not be loaded', true);
		}

		// Success
		if ($this->config->get('config_subscription_active_status_id') == $subscription_status_id) {
			// Trial
			if ($result['trial_status'] && (!$result['trial_duration'] || $result['trial_remaining'])) {
				if ($result['trial_duration'] && $result['trial_remaining']) {
					$this->model_account_subscription->editTrialRemaining($result['subscription_id'], $result['trial_remaining'] - 1);
				}
			} elseif (!$result['duration'] || $result['remaining']) {
				// Subscription
				if ($result['duration'] && $result['remaining']) {
					$this->model_account_subscription->editRemaining($result['subscription_id'], $result['remaining'] - 1);
				}
			}
		}
    }
}
