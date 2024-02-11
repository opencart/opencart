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
			'filter_date_next'              => date('Y-m-d H:i:s'),
			'filter_subscription_status_id' => $this->config->get('config_subscription_active_status_id'),
			'start'                         => 0,
			'limit'                         => 10
		];

		// Get all
		$this->load->model('checkout/subscription');
		$this->load->model('checkout/order');

		$results = $this->model_checkout_subscription->getSubscriptions($filter_data);

		foreach ($results as $result) {
			$order_info = $this->model_checkout_order->getOrder($result['order_id']);

			if (($result['trial_status'] && $result['trial_remaining']) || ($result['duration'] && $result['remaining'])) {
				$error = '';

				// 1. Language
				$this->load->model('localisation/language');

				$language_info = $this->model_localisation_language->getLanguage($result['language_id']);

				if (!$language_info) {
					$error = $this->language->get('error_language');
				}

				// 2. Currency
				$this->load->model('localisation/currency');

				$currency_info = $this->model_localisation_currency->getCurrency($result['currency_id']);

				if (!$currency_info) {
					$error = $this->language->get('error_currency');
				}

				// 3. Create new instance of a store
				if (!$error) {
					$this->load->model('setting/store');

					$store = $this->model_setting_store->createStoreInstance($result['store_id'], $language_info['code']);

					// Login
					$this->load->model('account/customer');

					$customer_info = $this->model_account_customer->getCustomer($result['customer_id']);

					if ($customer_info && $this->customer->login($customer_info['email'], '', true)) {
						// Add customer details into session
						$store->session->data['customer'] = $customer_info;
					} else {
						$error = $this->language->get('error_customer');
					}
				}

				// 4. Add product
				if (!$error) {
					$this->load->model('catalog/product');

					$product_info = $this->model_catalog_product->getProduct($result['product_id']);

					if ($product_info) {
						$price = $result['price'];

						if ($result['trial_status'] && (!$result['trial_duration'] || $result['trial_remaining'])) {
							$price = $result['trial_price'];
						}

						$store->cart->add($result['product_id'], $result['quantity'], $result['option'], $result['subscription_plan_id'], true, $price);
					} else {
						$error = $this->language->get('error_product');
					}
				}

				// 5. Add Shipping Address
				if (!$error && $store->cart->hasShipping()) {
					$this->load->model('account/address');

					$shipping_address_info = $this->model_account_address->getAddress($result['customer_id'], $result['shipping_address_id']);

					if ($shipping_address_info) {
						$store->session->data['shipping_address'] = $shipping_address_info;
					} else {
						$error = $this->language->get('error_shipping_address');
					}

					// 5. Shipping Methods
					if (!$error) {
						$this->load->model('checkout/shipping_method');

						$shipping_methods = $this->model_checkout_shipping_method->getMethods($shipping_address_info);

						// Validate shipping method
						if (isset($order_info['shipping_method']['code']) && $shipping_methods) {
							$shipping = explode('.', $order_info['shipping_method']['code']);

							if (isset($shipping[0]) && isset($shipping[1]) && isset($shipping_methods[$shipping[0]]['quote'][$shipping[1]])) {
								$store->session->data['shipping_method'] = $shipping_methods[$shipping[0]]['quote'][$shipping[1]];
							} else {
								$error = $this->language->get('error_shipping_method');
							}
						} else {
							$error = $this->language->get('error_shipping_method');
						}
					}
				}

				// 6. Payment Address
				if (!$error) {
					if ($this->config->get('config_checkout_payment_address')) {
						$this->load->model('account/address');

						$payment_address_info = $this->model_account_address->getAddress($order_info['customer_id'], $result['payment_address_id']);

						if ($payment_address_info) {
							$store->session->data['payment_address'] = $payment_address_info;
						} else {
							$error = $this->language->get('error_payment_address');
						}
					} else {
						$payment_address_info = [];
					}
				}

				// 7. Payment Methods
				if (!$error) {
					$this->load->model('checkout/payment_method');

					$payment_methods = $this->model_checkout_payment_method->getMethods($store->session->data['payment_address']);

					// Validate payment methods
					if (isset($order_info['payment_method']['code']) && $payment_methods) {
						$payment = explode('.', $order_info['payment_method']['code']);

						if (isset($payment[0]) && isset($payment[1]) && isset($payment_methods[$payment[0]]['option'][$payment[1]])) {
							$store->session->data['payment_method'] = $payment_methods[$payment[0]]['option'][$payment[1]];
						} else {
							$error = $this->language->get('error_payment_method');
						}
					} else {
						$error = $this->language->get('error_payment_method');
					}
				}

				if (!$error) {
					// Subscription
					$order_data = [];

					$order_data['subscription_id'] = $order_info['subscription_id'];

					// Store Details
					$order_data['invoice_prefix'] = $order_info['invoice_prefix'];
					$order_data['store_id'] = $order_info['store_id'];
					$order_data['store_name'] = $order_info['store_name'];
					$order_data['store_url'] = $order_info['store_url'];

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
					if ($store->cart->hasShipping()) {
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
							'product_id'   => $product['product_id'],
							'master_id'    => $product['master_id'],
							'name'         => $product['name'],
							'model'        => $product['model'],
							'option'       => $product['option'],
							'subscription' => [],
							'download'     => $product['download'],
							'quantity'     => $product['quantity'],
							'subtract'     => $product['subtract'],
							'price'        => $product['price'],
							'total'        => $product['total'],
							'tax'          => $this->tax->getTax($price, $product['tax_class_id']),
							'reward'       => $product['reward']
						];
					}

					// Vouchers can not be in subscriptions
					$order_data['vouchers'] = [];

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

					$order_data = array_merge($order_data, $total_data);

					$order_data['affiliate_id'] = 0;
					$order_data['commission'] = 0;
					$order_data['marketing_id'] = 0;
					$order_data['tracking'] = '';

					if (isset($this->session->data['tracking'])) {
						$subtotal = $this->cart->getSubTotal();

						// Affiliate
						if ($this->config->get('config_affiliate_status')) {
							$this->load->model('account/affiliate');

							$affiliate_info = $this->model_account_affiliate->getAffiliateByTracking($this->session->data['tracking']);

							if ($affiliate_info) {
								$order_data['affiliate_id'] = $affiliate_info['customer_id'];
								$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
								$order_data['tracking'] = $this->session->data['tracking'];
							}
						}

						$this->load->model('marketing/marketing');

						$marketing_info = $this->model_marketing_marketing->getMarketingByCode($this->session->data['tracking']);

						if ($marketing_info) {
							$order_data['marketing_id'] = $marketing_info['marketing_id'];
						}
					}

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

					$callable = [$this->{'model_extension_' . $extension_info['extension'] . '_payment_' . $extension_info['code']}, 'charge'];

					if (is_callable($callable)) {
						// Process payment
						$response_info = $this->{'model_extension_' . $order_data['payment_method']['extension'] . '_payment_' . $order_data['payment_method']['code']}->charge($this->customer->getId(), $this->session->data['order_id'], $order_info['total'], $order_data['payment_method']['code']);

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
					// Errors add to
					$this->model_checkout_subscription->addHistory($result['subscription_id'], $this->config->get('config_subscription_failed_status_id'), $error, true);
				}
			}
		}
	}
}
