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
			'start' => 0,
			'limit' => 10
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
			$error = [];

			// Create new instance of a store
			$store = $this->model_setting_store->createStoreInstance($result['store_id'], $result['language'], $result['currency']);

			// Set the store ID.
			if ($result['store_id']) {
				$this->load->model('setting/store');

				$store_info = $this->model_setting_store->getStore($result['store_id']);

				if (!$store_info) {
					$error['store'] = $this->language->get('error_store');
				}
			}

			// Validate language
			$language_info = $this->model_localisation_language->getLanguageByCode($result['language']);

			if ($language_info) {
				$store->request->get['language'] = $language_info['code'];

				$store->config->set('config_language_id', $language_info['language_id']);
				$store->config->set('config_language', $language_info['code']);
			} else {
				$error['language'] = $this->language->get('error_language');
			}

			// Validate currency
			$currency_info = $this->model_localisation_currency->getCurrencyByCode($result['currency']);

			if ($currency_info) {
				$store->session->data['currency'] = $currency_info['code'];
			} else {
				$error['currency'] = $this->language->get('error_currency');
			}

			// Validate customer
			$customer_info = $this->model_account_customer->getCustomer($result['customer_id']);

			if ($customer_info && $this->customer->login($customer_info['email'], '', true)) {
				$store->session->data['customer'] = $customer_info;
			} else {
				$error['customer'] = $this->language->get('error_customer');
			}

			// Validate payment address
			if ($result['payment_address_id']) {
				$payment_address_info = $this->model_account_address->getAddress($result['customer_id'], $result['payment_address_id']);

				if ($payment_address_info) {
					$store->session->data['payment_address'] = $payment_address_info;
				} else {
					$error['payment_address'] = $this->language->get('error_payment_address');
				}
			} else {
				$payment_address_info = [];
			}

			if ($result['shipping_address_id']) {
				// Validate shipping address
				$shipping_address_info = $this->model_account_address->getAddress($result['customer_id'], $result['shipping_address_id']);

				if ($shipping_address_info) {
					$store->session->data['shipping_address'] = $shipping_address_info;
				} else {
					$error['shipping_address'] = $this->language->get('error_shipping_address');
				}

				// Validate shipping method
				$keys = [
					'name',
					'code',
					'cost',
					'tax_class_id'
				];

				foreach ($keys as $key) {
					if (!isset($result['shipping_method'][$key])) {
						$error['shipping_method'] = $this->language->get('error_shipping_method');

						break;
					}
				}

				if (!isset($error['shipping_method'])) {
					$store->session->data['shipping_method'] = $result['shipping_method'];
				}
			}

			// Validate payment method
			$keys = [
				'name',
				'code'
			];

			foreach ($keys as $key) {
				if (!isset($result['payment_method'][$key])) {
					$error['payment_method'] = $this->language->get('error_payment_method');

					break;
				}
			}

			if (!isset($error['payment_method'])) {
				$store->session->data['payment_method'] = $result['payment_method'];
			}

			// Validate Products
			$products = $this->model_checkout_subscription->getProducts($result['subscription_id']);

			foreach ($products as $product) {
				$product_info = $store->model_catalog_product->getProduct($product['product_id']);

				if ($product_info) {
					$option_data = [];

					$options = $store->model_catalog_product->getOptions($product['product_id'], $product['order_product_id']);

					foreach ($options as $option) {
						if ($option['required']) {
							$option_info = $this->model_checkout_subscription->getOption($result['subscription_id'], $product['subscription_product_id'], $option['product_option_id']);

							if ($option_info && $option_info['value']) {
								$option_data[$option['product_option_id']] = $option_info['value'];
							} else {
								$error['option_' . $product['product_id'] . '_' . $option['product_option_id']] = sprintf($this->language->get('error_option'), $option['name']);
							}
						}
					}

					$price = $product['price'];

					if ($result['trial_status']) {
						$price = $result['trial_price'];
					}

					$store->cart->add($product['product_id'], $product['quantity'], $option_data, $result['subscription_plan_id'], ['price' => $price]);
				} else {
					$error['product_' . $product['product_id']] = $this->language->get('error_product');
				}
			}

			if (!$error) {
				// Subscription
				$order_data = [];

				$order_data['subscription_id'] = $result['subscription_id'];
				$order_data['invoice_prefix'] = $store->config->get('config_invoice_prefix');

				// Store Details
				$order_data['store_id'] = $store->config->get('config_store_id');
				$order_data['store_name'] = $store->config->get('config_name');
				$order_data['store_url'] = $store->config->get('config_url');

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

				$store->load->model('checkout/cart');

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

				($store->model_checkout_cart->getTotals)($totals, $taxes, $total);

				$total_data = [
					'totals' => $totals,
					'taxes'  => $taxes,
					'total'  => $total
				];

				$order_data = $order_data + $total_data;

				// Comment
				$order_data['comment'] = $result['comment'];

				$order_data['affiliate_id'] = 0;
				$order_data['commission'] = 0;
				$order_data['marketing_id'] = 0;
				$order_data['tracking'] = '';

				// Language
				$order_data['language_id'] = $store->config->get('config_language_id');
				$order_data['language_code'] = $store->config->get('config_language');

				// Currency
				$order_data['currency_id'] = $store->currency->getId($store->session->data['currency']);
				$order_data['currency_code'] = $store->session->data['currency'];
				$order_data['currency_value'] = $store->currency->getValue($store->session->data['currency']);

				$this->load->model('checkout/order');

				$order_info = $this->model_checkout_order->getOrder($result['order_id']);

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

				// Validate if payment extension installed
				if (isset($this->session->data['payment_method'])) {
					$code = oc_substr($this->session->data['payment_method']['code'], 0, strpos($this->session->data['payment_method']['code'], '.'));
				} else {
					$code = '';
				}

				$this->load->model('setting/extension');

				$extension_info = $this->model_setting_extension->getExtensionByCode('payment', $code);

				if ($extension_info) {
					// Load payment method used by the subscription
					$this->load->model('extension/' . $extension_info['extension'] . '/payment/' . $extension_info['code']);

					$key = 'model_extension_' . $extension_info['extension'] . '_payment_' . $extension_info['code'];

					echo '$key';

					if (isset($store->{$key}->charge)) {

						echo '$this->{$key}->charge';

						// Process payment
						$response_info = $this->{$key}->charge($this->customer->getId(), $store->session->data['order_id'], $total, $order_data['payment_method']['code']);

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

						$store->model_checkout_order->addHistory($store->session->data['order_id'], $order_status_id, $message, false);
						$store->model_checkout_order->addHistory($store->session->data['order_id'], $order_status_id);

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
								$store->load->model('checkout/subscription');

								$store->model_checkout_subscription->editDateNext($result['subscription_id'], $date_next);
							}

							$store->model_checkout_subscription->addHistory($result['subscription_id'], $this->config->get('config_subscription_active_status_id'), $this->language->get('text_success'));

						} else {

							echo '';

							// If payment failed change subscription history to failed
							$this->model_checkout_subscription->addHistory($result['subscription_id'], $this->config->get('config_subscription_failed_status_id'), $message);
						}

					} else {

						// Add subscription history failed if no charge method
						$this->model_checkout_subscription->addHistory($result['subscription_id'], $this->config->get('config_subscription_failed_status_id'), $this->language->get('error_payment_method'));
					}

				} else {

				}

			} else {
				// Errors
				$this->model_checkout_subscription->addHistory($result['subscription_id'], $this->config->get('config_subscription_failed_status_id'), $this->language->get('text_log'));

				// Log errors
				foreach ($error as $key => $value) {
					$this->model_checkout_subscription->addLog($result['subscription_id'], $key, $value);
				}
			}

			// 7. Clean up data by clearing cart.
			$store->cart->clear();

			// 8. Deleting the current session so we are not creating infinite sessions.
			$store->session->destroy();
		}
		//}
	}
}
