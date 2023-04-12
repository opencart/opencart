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

				// 1. create new instance of a store
				$store = $this->model_setting_store->createStoreInstance($order_info['store_id'], $order_info['language_code']);

				// 2. Login
				$customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

				if ($customer_info && $this->customer->login($customer_info['email'], '', true)) {
					// Add customer details into session
					$store->session->data['customer'] = [
						'customer_id'       => $customer_info['customer_id'],
						'customer_group_id' => $customer_info['customer_group_id'],
						'firstname'         => $customer_info['firstname'],
						'lastname'          => $customer_info['lastname'],
						'email'             => $customer_info['email'],
						'telephone'         => $customer_info['telephone'],
						'custom_field'      => $customer_info['custom_field']
					];
				} else {
					$error .= $this->language->get('error_customer') . "\n";
				}

				// 3. Add product
				$product_info = $this->model_checkout_order->getProduct($result['order_id']);

				if ($product_info) {
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

					$store->cart->add($result['product_id'], $result['quantity'], $option_data);
				} else {
					$error .= $this->language->get('error_product');
				}

				// 4.  Add Shipping Address
				if ($store->cart->hasShipping()) {
					$shipping_address_info = $this->model_account_address->getAddress($order_info['customer_id'], $order_info['shipping_address_id']);

					if ($shipping_address_info) {
						$this->session->data['shipping_address'] = $shipping_address_info;
					} else {
						$error = $this->language->get('error_shipping_address');
					}

					// Shipping Methods
					$this->load->model('checkout/shipping_method');

					$shipping_methods = $this->model_checkout_shipping_method->getMethods($shipping_address);

					// Validate shipping method
					if ($order_info['shipping_code'] && $shipping_methods) {
						$shipping = explode('.', $order_info['shipping_code']);

						if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($shipping_methods[$shipping[0]]['quote'][$shipping[1]])) {
							$error .= $this->language->get('error_shipping_method');
						}
					} else {
						$error .= $this->language->get('error_shipping_method');
					}
				}

				// Payment Address
				if ($this->config->get('config_checkout_payment_address')) {
					$payment_address_info = $this->model_account_address->getAddress($order_info['customer_id'], $result['payment_address_id']);

					if ($payment_address_info) {
						$this->session->data['payment_address'] = $payment_address_info;
					} else {
						$error = $this->language->get('error_payment_address');
					}
				} else {
					$payment_address = [];
				}

				// Payment Methods
				$this->load->model('checkout/payment_method');

				$payment_methods = $this->model_checkout_payment_method->getMethods($payment_address);

				// Validate payment methods
				if ($order_info['payment_code'] && $payment_methods) {
					$payment = explode('.', $order_info['payment_code']);

					if (isset($payment[0]) || isset($payment[1]) || isset($payment_methods[$payment[0]]['option'][$payment[1]])) {
						$error = $this->language->get('error_payment_method');
					}
				} else {
					$error = $this->language->get('error_payment_method');
				}


				if (!$error) {

					// Order Totals
					$totals = [];
					$taxes = $store->cart->getTaxes();
					$total = 0;

					$store->load->model('checkout/cart');

					($store->model_checkout_cart->getTotals)($totals, $taxes, $total);

					$data['totals'] = [];

					foreach ($totals as $total) {
						$data['totals'][] = [
							'title' => $total['title'],
							'text'  => $store->currency->format($total['value'], $this->session->data['currency'])
						];
					}


					// Store Details
					$order_data['invoice_prefix'] = $order_info['invoice_prefix'];
					$order_data['store_id'] = $order_info['store_id'];
					$order_data['store_name'] = $this->config->get('config_name');
					$order_data['store_url'] = $this->config->get('config_url');

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

					$order_data['payment_method'] = $this->session->data['payment_method'];

					// Shipping Details
					if ($shipping_address) {
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

					// Products
					$order_data['products'] = [];

					$products = $this->model_checkout_cart->getProducts();

					foreach ($products as $product) {
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
							if ($product['subscription']['trial_duration'] && $product['subscription']['trial_remaining']) {
								$date_next = date('Y-m-d', strtotime('+' . $product['subscription']['trial_cycle'] . ' ' . $product['subscription']['trial_frequency']));
							} elseif ($product['subscription']['duration'] && $product['subscription']['remaining']) {
								$date_next = date('Y-m-d', strtotime('+' . $product['subscription']['cycle'] . ' ' . $product['subscription']['frequency']));
							}

							$subscription_data = [
								'subscription_plan_id' => $product['subscription']['subscription_plan_id'],
								'name'                 => $product['subscription']['name'],
								'trial_price'          => $product['subscription']['trial_price'],
								'trial_frequency'      => $product['subscription']['trial_frequency'],
								'trial_cycle'          => $product['subscription']['trial_cycle'],
								'trial_duration'       => $product['subscription']['trial_duration'],
								'trial_remaining'      => $product['subscription']['trial_remaining'],
								'trial_status'         => $product['subscription']['trial_status'],
								'price'                => $product['subscription']['price'],
								'frequency'            => $product['subscription']['frequency'],
								'cycle'                => $product['subscription']['cycle'],
								'duration'             => $product['subscription']['duration'],
								'remaining'            => $product['subscription']['duration'],
								'date_next'            => $date_next
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
					}
				}


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
		/*
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
		*/
    }
}
