<?php
namespace Opencart\Catalog\Controller\Cron;
/**
 * Class Subscription
 *
 * @package Opencart\Catalog\Controller\Cron
 */
class Subscription extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @param int    $cron_id
	 * @param string $code
	 * @param string $cycle
	 * @param string $date_added
	 * @param string $date_modified
	 *
	 * @return void
	 */
	public function index(int $cron_id, string $code, string $cycle, string $date_added, string $date_modified): void {
		$this->load->language('cron/subscription');

		// Check if there is an order, the order status is complete and subscription status is active
		$filter_data = [
			//'filter_date_next'              => '2025-10-31',
			//'filter_date_next'              => date('Y-m-d H:i:s'),
			//'filter_subscription_status_id' => $this->config->get('config_subscription_active_status_id'),
			'start'                         => 0,
			'limit'                         => 10
		];

		$this->load->model('setting/store');
		$this->load->model('localisation/language');
		$this->load->model('localisation/currency');

		$this->load->model('checkout/subscription');
		$this->load->model('checkout/order');

		$this->load->model('account/customer');
		$this->load->model('account/address');

		$this->load->model('catalog/product');

		$results = $this->model_checkout_subscription->getSubscriptions($filter_data);

		print_r($results);

		foreach ($results as $result) {
			//if (($result['trial_status'] && $result['trial_remaining']) || (!$result['duration'] && $result['remaining'])) {
				$status = true;

				// Validate store
				if ($result['store_id']) {
					$store_info = $this->model_setting_store->getStore($result['store_id']);

					if (!$store_info) {
						$status = false;

						$this->model_checkout_subscription->addLog($result['subscription_id'], 'store', $this->language->get('error_store'));
					}
				}

				// Validate language
				$language_info = $this->model_localisation_language->getLanguage($result['language_id']);

				if (!$language_info) {
					$status = false;

					$this->model_checkout_subscription->addLog($result['subscription_id'], 'language', $this->language->get('error_language'));
				}

				// Validate currency
				$currency_info = $this->model_localisation_currency->getCurrency($result['currency_id']);

				if (!$currency_info) {
					$status = false;

					$this->model_checkout_subscription->addLog($result['subscription_id'], 'currency', $this->language->get('error_currency'));
				}

				// Validate customer
				$customer_info = $this->model_account_customer->getCustomer($result['customer_id']);

				if (!$customer_info || !$this->customer->login($customer_info['email'], '', true)) {
					$status = false;

					$this->model_checkout_subscription->addLog($result['subscription_id'], 'customer', $this->language->get('error_customer'));
				}

				// Validate payment address
				if ($result['payment_address_id']) {
					$payment_address_info = $this->model_account_address->getAddress($result['customer_id'], $result['payment_address_id']);

					if (!$payment_address_info) {
						$status = false;

						$this->model_chekout_subscription->addLog($result['subscription_id'], 'payment_address', $this->language->get('error_payment_address'));
					}
				} else {
					$payment_address_info = [];
				}

				if ($result['shipping_address_id']) {
					// Validate shipping address
					$shipping_address_info = $this->model_account_address->getAddress($result['customer_id'], $result['shipping_address_id']);

					if (!$shipping_address_info) {
						$status = false;

						$this->model_checkout_subscription->addLog($result['subscription_id'], 'shipping_address', $this->language->get('error_shipping_address'));
					}

					// Validate shipping method
					$error = '';

					$keys = [
						'name',
						'code',
						'cost',
						'tax_class_id'
					];

					foreach ($keys as $key) {
						if (!isset($result['shipping_method'][$key])) {
							$error = $this->language->get('error_shipping_method');

							break;
						}
					}

					if ($error) {
						$status = false;

						$this->model_checkout_subscription->addLog($result['subscription_id'], 'shipping_method', $error);
					}
				}

				// Validate payment method
				$error = '';

				$keys = [
					'name',
					'code'
				];

				foreach ($keys as $key) {
					if (!isset($result['payment_method'][$key])) {
						$error = $this->language->get('error_payment_method');

						break;
					}
				}

				if ($error) {
					$status = false;

					$this->model_checkout_subscription->addLog($result['subscription_id'], 'payment_method', $this->language->get('error_payment_method'));
				}

				// Validate Products
				$products = $this->model_checkout_subscription->getProducts($result['subscription_id']);

				foreach ($products as $product) {
					$product_info = $this->model_catalog_product->getProduct($product['product_id']);

					if ($product_info) {
						$options = $this->model_catalog_product->getOptions($product['product_id'], $product['order_product_id']);

						foreach ($options as $option) {
							$option_info = $this->model_checkout_subscription->getOption($result['subscription_id'], $product['subscription_product_id'], $option['product_option_id']);

							if ($option['required'] && (!$option_info || !$option_info['value'])) {
								$status = false;

								$this->model_checkout_subscription->addLog($result['subscription_id'], 'option', $this->language->get('error_option'));
							}
						}
					} else {
						$status = false;

						$this->model_checkout_subscription->addLog($result['subscription_id'], 'product', $this->language->get('error_product'));
					}
				}

				echo '$status ' . $status;

				if ($status) {
					// Create new instance of a store
					$store = $this->model_setting_store->createStoreInstance($result['store_id'], $language_info['code'], $currency_info['code']);

					// Add customer details into session
					$store->session->data['customer'] = $customer_info;
					$store->session->data['shipping_address'] = $shipping_address_info;
					$store->session->data['payment_address'] = $payment_address_info;

					foreach ($products as $product) {
						$price = $product['price'];

						if ($result['trial_status']) {
							$price = $result['trial_price'];
						}

						$store->cart->add($result['product_id'], $result['quantity'], $result['option'], $result['subscription_plan_id'], true, ['price' => $price]);
					}

					$store->session->data['shipping_method'] = $result['shipping_method'];
					$store->session->data['payment_method'] = $result['payment_method'];

					// Subscription
					$order_data = [];

					$order_data['subscription_id'] = $result['subscription_id'];
					$order_data['invoice_prefix'] = $result['invoice_prefix'];

					// Store Details
					$order_data['store_id'] = $store_info['store_id'];
					$order_data['store_name'] = $store_info['store_name'];
					$order_data['store_url'] = $store_info['store_url'];

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

					$order_data['payment_method'] = $store->session->data['payment_method'];

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

						$order_data['shipping_method'] = $store->session->data['shipping_method'];
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

					$products = $store->model_checkout_cart->getProducts();

					foreach ($products as $product) {
						$order_data['products'][] = [
							'subscription' => [],
							'tax'          => $this->tax->getTax($price, $product['tax_class_id'])
						] + $product;
					}

					// Order Totals
					$totals = [];
					$taxes = $store->cart->getTaxes();
					$total = 0;

					$store->load->model('checkout/cart');

					($store->model_checkout_cart->getTotals)($totals, $taxes, $total);

					$total_data = [
						'totals' => $totals,
						'taxes'  => $taxes,
						'total'  => $total
					];

					$order_data = $order_data + $total_data;

					// Language
					$order_data['language_id'] = $language_info['language_id'];
					$order_data['language_code'] = $language_info['code'];

					// Currency
					$order_data['currency_id'] = $currency_info['currency_id'];
					$order_data['currency_code'] = $currency_info['code'];
					$order_data['currency_value'] = $currency_info['value'];

					$order_data['ip'] = $result['ip'];
					$order_data['forwarded_ip'] = $result['forwarded_ip'];
					$order_data['user_agent'] = $result['user_agent'];
					$order_data['accept_language'] = $result['accept_language'];

					$this->load->model('checkout/order');

					$store->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);

					// Validate if payment extension installed
					$this->load->model('setting/extension');

					$extension_info = $this->model_setting_extension->getExtensionByCode('payment', $payment[0]);

					// Load payment method used by the subscription
					$this->load->model('extension/' . $extension_info['extension'] . '/payment/' . $extension_info['code']);

					$key = 'model_extension_' . $extension_info['extension'] . '_payment_' . $extension_info['code'];

					if (isset($this->{$key}->charge)) {
						// Process payment
						$response_info = $this->{$key}->charge($this->customer->getId(), $this->session->data['order_id'], $order_info['total'], $order_data['payment_method']['code']);

						if (isset($response_info['order_status_id'])) {
							$order_status_id = $response_info['order_status_id'];
						} else {
							$order_status_id = 0;
						}

						if ($response_info['message']) {
							$message = $response_info['message'];
						} else {
							$message = '';
						}

						$this->model_checkout_order->addHistory($store->session->data['order_id'], $order_status_id, $message, false);
						$this->model_checkout_order->addHistory($store->session->data['order_id'], $order_status_id);

						// If payment order status is active or processing
						if (!in_array($order_status_id, (array)$this->config->get('config_processing_status') + (array)$this->config->get('config_complete_status'))) {
							$remaining = 0;
							$date_next = '';

							if ($result['trial_status'] && $result['trial_remaining'] > 1) {
								$remaining = $result['trial_remaining'] - 1;
								$date_next = date('Y-m-d', strtotime('+' . $result['trial_cycle'] . ' ' . $result['trial_frequency']));

								$this->model_account_subscription->editTrialRemaining($result['subscription_id'], $remaining);
							} elseif ($result['duration'] && $result['remaining']) {
								$remaining = $result['remaining'] - 1;
								$date_next = date('Y-m-d', strtotime('+' . $result['cycle'] . ' ' . $result['frequency']));

								// If duration make sure there is remaining
								$this->model_account_subscription->editRemaining($result['subscription_id'], $remaining);
							} elseif (!$result['duration']) {
								// If duration is unlimited
								$date_next = date('Y-m-d', strtotime('+' . $result['cycle'] . ' ' . $result['frequency']));
							}

							if ($date_next) {
								$this->load->model('checkout/subscription');

								$this->model_checkout_subscription->editDateNext($result['subscription_id'], $date_next);
							}

							$this->model_checkout_subscription->addHistory($result['subscription_id'], $this->config->get('config_subscription_active_status_id'), $this->language->get('text_success'), true);
						} else {
							// If payment failed change subscription history to failed
							$this->model_checkout_subscription->addHistory($result['subscription_id'], $this->config->get('config_subscription_failed_status_id'), $message, true);
						}
					} else {
						// Add subscription history failed if no charge method
						$this->model_checkout_subscription->addHistory($result['subscription_id'], $this->config->get('config_subscription_failed_status_id'), $this->language->get('error_payment_method'), true);
					}
				} else {
					// Errors
					$this->model_checkout_subscription->addHistory($result['subscription_id'], $this->config->get('config_subscription_failed_status_id'), $error, true);
				}



				// 6. Call the required API controller and get the output.
				$output = $store->response->getOutput();

				// 7. Clean up data by clearing cart.
				$store->cart->clear();

				// 8. Deleting the current session so we are not creating infinite sessions.
				$store->session->destroy();



			}

	//	}

	}
}
