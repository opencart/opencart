<?php
/**
 * Class PayPal
 *
 * @package Opencart\Catalog\Model\Extension\PayPal\Payment
 */
namespace Opencart\Catalog\Model\Extension\PayPal\Payment;
class PayPal extends \Opencart\System\Engine\Model {
	/**
	 * getMethod
	 *
	 * @param array $address
	 *
	 * @return array
	 */
	public function getMethod(array $address): array {
		$method_data = [];

		$agree_status = $this->getAgreeStatus();

		if ($this->config->get('payment_paypal_status') && $this->config->get('payment_paypal_client_id') && $this->config->get('payment_paypal_secret') && $agree_status) {
			$this->load->language('extension/paypal/payment/paypal');

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$this->config->get('payment_paypal_geo_zone_id') . "' AND `country_id` = '" . (int)$address['country_id'] . "' AND (`zone_id` = '" . (int)$address['zone_id'] . "' OR `zone_id` = '0')");

			if (!$this->config->get('payment_paypal_geo_zone_id')) {
				$status = true;
			} elseif ($query->num_rows) {
				$status = true;
			} else {
				$status = false;
			}

			if ($status) {
				$method_data = [
					'code'       => 'paypal',
					'title'      => $this->language->get('text_paypal_title'),
					'sort_order' => $this->config->get('payment_paypal_sort_order')
				];
			}
		}

		return $method_data;
	}

	/**
	 * getMethods
	 *
	 * @param array $address
	 *
	 * @return array
	 */
	public function getMethods(array $address = []): array {
		$method_data = [];

		$agree_status = $this->getAgreeStatus();

		if ($this->config->get('payment_paypal_status') && $this->config->get('payment_paypal_client_id') && $this->config->get('payment_paypal_secret') && $agree_status) {
			$this->load->language('extension/paypal/payment/paypal');

			if (!$this->config->get('config_checkout_payment_address')) {
				$status = true;
			} elseif (!$this->config->get('payment_paypal_geo_zone_id')) {
				$status = true;
			} else {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$this->config->get('payment_paypal_geo_zone_id') . "' AND `country_id` = '" . (int)$address['country_id'] . "' AND (`zone_id` = '" . (int)$address['zone_id'] . "' OR `zone_id` = '0')");

				if ($query->num_rows) {
					$status = true;
				} else {
					$status = false;
				}
			}

			if ($status) {
				$_config = new \Opencart\System\Engine\Config();
				$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
				$_config->load('paypal');

				$config_setting = $_config->get('paypal_setting');

				$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));

				$option_data['paypal'] = [
					'code' => 'paypal.paypal',
					'name' => $this->language->get('text_paypal_title')
				];

				if (!empty($setting['paylater_country'][$setting['general']['country_code']]) && ($setting['button']['checkout']['funding']['paylater'] != 2)) {
					$option_data['paylater'] = [
						'code' => 'paypal.paylater',
						'name' => $this->language->get('text_paypal_paylater_title')
					];
				}

				if ($setting['googlepay_button']['status']) {
					$option_data['googlepay'] = [
						'code' => 'paypal.googlepay',
						'name' => $this->language->get('text_paypal_googlepay_title')
					];
				}

				if ($setting['applepay_button']['status'] && $this->isApple()) {
					$option_data['applepay'] = [
						'code' => 'paypal.applepay',
						'name' => $this->language->get('text_paypal_applepay_title')
					];
				}

				$method_data = [
					'code'       => 'paypal',
					'name'       => $this->language->get('text_paypal'),
					'option'     => $option_data,
					'sort_order' => $this->config->get('payment_paypal_sort_order')
				];
			}
		}

		return $method_data;
	}

	/**
	 * hasProductInCart
	 *
	 * @param int   $product_id
	 * @param array $option
	 * @param int   $subscription_plan_id
	 *
	 * @return array
	 */
	public function hasProductInCart(int $product_id, array $option = [], int $subscription_plan_id = 0): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "cart` WHERE `api_id` = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND `customer_id` = '" . (int)$this->customer->getId() . "' AND `session_id` = '" . $this->db->escape($this->session->getId()) . "' AND `product_id` = '" . (int)$product_id . "' AND `subscription_plan_id` = '" . (int)$subscription_plan_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "'");

		return (int)$query->row['total'];
	}

	/**
	 * getCountryCode
	 *
	 * @param string $code
	 *
	 * @return array
	 */
	public function getCountryByCode(string $code): array {
		$query = $this->db->query("SELECT *, c.`name` FROM `" . DB_PREFIX . "country` c LEFT JOIN `" . DB_PREFIX . "address_format` `af` ON (c.`address_format_id` = `af`.`address_format_id`) WHERE c.`iso_code_2` = '" . $this->db->escape($code) . "' AND c.`status` = '1'");

		return $query->row;
	}

	/**
	 * getZoneByCode
	 *
	 * @param int    $country_id
	 * @param string $code
	 *
	 * @return array
	 */
	public function getZoneByCode(int $country_id, string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE `country_id` = '" . (int)$country_id . "' AND (`code` = '" . $this->db->escape($code) . "' OR `name` = '" . $this->db->escape($code) . "') AND `status` = '1'");

		return $query->row;
	}

	/**
	 * addPayPalOrder
	 *
	 * @param array $data
	 *
	 * @return void
	 */
	public function addPayPalOrder(array $data): void {
		$sql = "INSERT INTO `" . DB_PREFIX . "paypal_checkout_integration_order` SET";

		$implode = [];

		if (!empty($data['order_id'])) {
			$implode[] = "`order_id` = '" . (int)$data['order_id'] . "'";
		}

		if (!empty($data['transaction_id'])) {
			$implode[] = "`transaction_id` = '" . $this->db->escape($data['transaction_id']) . "'";
		}

		if (!empty($data['transaction_status'])) {
			$implode[] = "`transaction_status` = '" . $this->db->escape($data['transaction_status']) . "'";
		}

		if (!empty($data['payment_method'])) {
			$implode[] = "`payment_method` = '" . $this->db->escape($data['payment_method']) . "'";
		}

		if (!empty($data['vault_id'])) {
			$implode[] = "`vault_id` = '" . $this->db->escape($data['vault_id']) . "'";
		}

		if (!empty($data['vault_customer_id'])) {
			$implode[] = "`vault_customer_id` = '" . $this->db->escape($data['vault_customer_id']) . "'";
		}

		if (!empty($data['environment'])) {
			$implode[] = "`environment` = '" . $this->db->escape($data['environment']) . "'";
		}

		if ($implode) {
			$sql .= implode(", ", $implode);
		}

		$this->db->query($sql);
	}

	/**
	 * editPayPalOrder
	 *
	 * @param array $data
	 *
	 * @return void
	 */
	public function editPayPalOrder(array $data): void {
		$sql = "UPDATE `" . DB_PREFIX . "paypal_checkout_integration_order` SET";

		$implode = [];

		if (!empty($data['transaction_id'])) {
			$implode[] = "`transaction_id` = '" . $this->db->escape($data['transaction_id']) . "'";
		}

		if (!empty($data['transaction_status'])) {
			$implode[] = "`transaction_status` = '" . $this->db->escape($data['transaction_status']) . "'";
		}

		if (!empty($data['payment_method'])) {
			$implode[] = "`payment_method` = '" . $this->db->escape($data['payment_method']) . "'";
		}

		if (!empty($data['vault_id'])) {
			$implode[] = "`vault_id` = '" . $this->db->escape($data['vault_id']) . "'";
		}

		if (!empty($data['vault_customer_id'])) {
			$implode[] = "`vault_customer_id` = '" . $this->db->escape($data['vault_customer_id']) . "'";
		}

		if (!empty($data['environment'])) {
			$implode[] = "`environment` = '" . $this->db->escape($data['environment']) . "'";
		}

		if ($implode) {
			$sql .= implode(", ", $implode);
		}

		$sql .= " WHERE `order_id` = '" . (int)$data['order_id'] . "'";

		$this->db->query($sql);
	}

	/**
	 * deletePayPalOrder
	 *
	 * @param int $order_id
	 *
	 * @return void
	 */
	public function deletePayPalOrder(int $order_id): void {
		$query = $this->db->query("DELETE FROM `" . DB_PREFIX . "paypal_checkout_integration_order` WHERE `order_id` = '" . (int)$order_id . "'");
	}

	/**
	 * getPayPalOrder
	 *
	 * @param int $order_id
	 *
	 * @return array
	 */
	public function getPayPalOrder(int $order_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "paypal_checkout_integration_order` WHERE `order_id` = '" . (int)$order_id . "'");

		if ($query->num_rows) {
			return $query->row;
		} else {
			return [];
		}
	}

	/**
	 * editSubscriptionStatus
	 *
	 * @param int $subscription_id
	 * @param int $subscription_status_id
	 *
	 * @return void
	 */
	public function editSubscriptionStatus(int $subscription_id, int $subscription_status_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `subscription_status_id` = '" . (int)$subscription_status_id . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/**
	 * editSubscriptionRemainingDateNext
	 *
	 * @param int    $subscription_id
	 * @param int    $remaining
	 * @param int    $trial_remaining
	 * @param string $date_next
	 *
	 * @return void
	 */
	public function editSubscriptionRemainingDateNext(int $subscription_id, int $remaining, int $trial_remaining, string $date_next): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "subscription` SET `remaining` = '" . (int)$remaining . "', `trial_remaining` = '" . (int)$trial_remaining . "', `date_next` = '" . $this->db->escape($date_next) . "' WHERE `subscription_id` = '" . (int)$subscription_id . "'");
	}

	/**
	 * getSubscriptions
	 *
	 * @return array
	 */
	public function getSubscriptions(): array {
		if (VERSION >= '4.0.2.0') {
			$query = $this->db->query("SELECT `s`.`subscription_id` FROM `" . DB_PREFIX . "subscription` `s` JOIN `" . DB_PREFIX . "order` `o` USING(`order_id`) WHERE `s`.`subscription_status_id` = '" . (int)$this->config->get('config_subscription_active_status_id') . "' AND DATE(`s`.`date_next`) <= NOW() AND `o`.`payment_method` LIKE '%paypal%'");
		} else {
			$query = $this->db->query("SELECT `s`.`subscription_id` FROM `" . DB_PREFIX . "subscription` `s` JOIN `" . DB_PREFIX . "order` `o` USING(`order_id`) WHERE `s`.`subscription_status_id` = '" . (int)$this->config->get('config_subscription_active_status_id') . "' AND DATE(`s`.`date_next`) <= NOW() AND `o`.`payment_code` = 'paypal'");
		}

		$subscription_data = [];

		foreach ($query->rows as $subscription) {
			$subscription_data[] = $this->getSubscription($subscription['subscription_id']);
		}

		return $subscription_data;
	}

	/**
	 * getSubscriptionsByOrderId
	 *
	 * @param int $order_id
	 *
	 * @return array
	 */
	public function getSubscriptionsByOrderId(int $order_id): array {
		if (VERSION >= '4.0.2.0') {
			$query = $this->db->query("SELECT `s`.`subscription_id` FROM `" . DB_PREFIX . "subscription` `s` JOIN `" . DB_PREFIX . "order` `o` USING(`order_id`) WHERE `s`.`order_id` = '" . (int)$order_id . "' AND `o`.`payment_method` LIKE '%paypal%'");
		} else {
			$query = $this->db->query("SELECT `s`.`subscription_id` FROM `" . DB_PREFIX . "subscription` `s` JOIN `" . DB_PREFIX . "order` `o` USING(`order_id`) WHERE `s`.`order_id` = '" . (int)$order_id . "' AND `o`.`payment_code` = 'paypal'");
		}

		$subscription_data = [];

		foreach ($query->rows as $subscription) {
			$subscription_data[] = $this->getSubscription($subscription['subscription_id']);
		}

		return $subscription_data;
	}

	/**
	 * getSubscription
	 *
	 * @param int $subscription_id
	 *
	 * @return array
	 */
	public function getSubscription(int $subscription_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription` WHERE `subscription_id` = '" . (int)$subscription_id . "'");

		return $query->row;
	}

	/**
	 * addSubscriptionTransaction
	 *
	 * @param mixed $data
	 *
	 * @return void
	 */
	public function addSubscriptionTransaction($data): void {
		if (VERSION < '4.0.2.0') {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "subscription_transaction` SET `subscription_id` = '" . (int)$data['subscription_id'] . "', `order_id` = '" . (int)$data['order_id'] . "', `transaction_id` = '" . (int)$data['transaction_id'] . "', `amount` = '" . (float)$data['amount'] . "', `date_added` = NOW()");
		} else {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "paypal_subscription_transaction` SET `subscription_id` = '" . (int)$data['subscription_id'] . "', `order_id` = '" . (int)$data['order_id'] . "', `transaction_id` = '" . (int)$data['transaction_id'] . "', `amount` = '" . (float)$data['amount'] . "', `date_added` = NOW()");
		}
	}

	/**
	 * subscriptionPayment
	 *
	 * @param mixed $subscription_data
	 * @param mixed $order_data
	 * @param mixed $paypal_order_data
	 *
	 * @return void
	 */
	public function subscriptionPayment($subscription_data, $order_data, $paypal_order_data): void {
		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
		$_config->load('paypal');

		$config_setting = $_config->get('paypal_setting');

		$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));

		$transaction_method = $setting['general']['transaction_method'];

		$subscription_id = $subscription_data['subscription_id'];
		$subscription_name = '';

		if ($subscription_data['trial_status'] && $subscription_data['trial_duration'] && $subscription['trial_remaining']) {
			$trial_remaining = $subscription_data['trial_duration'] - 1;
			$remaining = $subscription_data['duration'];
		} elseif ($subscription_data['duration'] && $subscription['remaining']) {
			$trial_remaining = $subscription_data['trial_duration'];
			$remaining = $subscription_data['duration'] - 1;
		} else {
			$trial_remaining = $subscription_data['trial_duration'];
			$remaining = $subscription_data['duration'];
		}

		$date_next = $subscription_data['date_next'];

		if ($subscription_data['trial_status'] && $subscription_data['trial_duration']) {
			$date_next = date('Y-m-d', strtotime('+' . $subscription_data['trial_cycle'] . ' ' . $subscription_data['trial_frequency']));
		} elseif ($subscription_data['duration'] && $subscription_data['remaining']) {
			$date_next = date('Y-m-d', strtotime('+' . $subscription_data['cycle'] . ' ' . $subscription_data['frequency']));
		}

		$this->editSubscriptionStatus($subscription_id, $this->config->get('config_subscription_active_status_id'));
		$this->editSubscriptionRemainingDateNext($subscription_id, $remaining, $trial_remaining, $date_next);
	}

	/**
	 * cronPayment
	 *
	 * @return void
	 */
	public function cronPayment(): void {
		$this->load->model('checkout/order');
		$this->load->model('catalog/product');

		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
		$_config->load('paypal');

		$config_setting = $_config->get('paypal_setting');

		$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));

		$transaction_method = $setting['general']['transaction_method'];

		$subscriptions = $this->getSubscriptions();

		foreach ($subscriptions as $subscription) {
			if ($subscription['subscription_status_id'] == $this->config->get('config_subscription_active_status_id')) {
				$order_info = $this->model_checkout_order->getOrder($subscription['order_id']);

				$paypal_order_info = $this->getPayPalOrder($subscription['order_id']);

				if ($subscription['trial_status'] && $subscription['trial_duration'] && $subscription['trial_remaining']) {
					$trial_remaining = $subscription['trial_remaining'] - 1;
					$remaining = $subscription['duration'];
				} elseif ($subscription['duration'] && $subscription['remaining']) {
					$trial_remaining = $subscription['trial_duration'];
					$remaining = $subscription['remaining'] - 1;
				} else {
					$trial_remaining = $subscription['trial_remaining'];
					$remaining = $subscription['remaining'];
				}

				$date_next = $subscription['date_next'];

				if ($subscription['trial_status'] && $subscription['trial_duration']) {
					$date_next = date('Y-m-d', strtotime('+' . $subscription['trial_cycle'] . ' ' . $subscription['trial_frequency']));
				} elseif ($subscription['duration'] && $subscription['remaining']) {
					$date_next = date('Y-m-d', strtotime('+' . $subscription['cycle'] . ' ' . $subscription['frequency']));
				}

				$price = 0;

				if ($subscription['trial_status'] && (!$subscription['trial_duration'] || $subscription['trial_remaining'])) {
					$price = $subscription['trial_price'];
				} elseif (!$subscription['duration'] || $subscription['remaining']) {
					$price = $subscription['price'];
				}

				$subscription_name = '';

				if (VERSION >= '4.0.2.0') {
					$product_info = $this->model_catalog_product->getProduct($subscription['product_id']);

					if ($product_info) {
						$subscription_name = $product_info['name'];
					}
				} else {
					$subscription_name = $subscription['name'];
				}

				$result = $this->createPayment($order_info, $paypal_order_info, $price, $subscription['subscription_id'], $subscription_name);

				$transaction_status = '';
				$transaction_id = '';
				$currency_code = '';
				$amount = '';

				if ($transaction_method == 'authorize') {
					if (isset($result['purchase_units'][0]['payments']['authorizations'][0]['status']) && isset($result['purchase_units'][0]['payments']['authorizations'][0]['seller_protection']['status'])) {
						$transaction_id = $result['purchase_units'][0]['payments']['authorizations'][0]['id'];
						$transaction_status = $result['purchase_units'][0]['payments']['authorizations'][0]['status'];
						$currency_code = $result['purchase_units'][0]['payments']['authorizations'][0]['amount']['currency_code'];
						$amount = $result['purchase_units'][0]['payments']['authorizations'][0]['amount']['value'];
					}
				} else {
					if (isset($result['purchase_units'][0]['payments']['captures'][0]['status']) && isset($result['purchase_units'][0]['payments']['captures'][0]['seller_protection']['status'])) {
						$transaction_id = $result['purchase_units'][0]['payments']['captures'][0]['id'];
						$transaction_status = $result['purchase_units'][0]['payments']['captures'][0]['status'];
						$currency_code = $result['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'];
						$amount = $result['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
					}
				}

				if ($transaction_id && $transaction_status && $currency_code && $amount) {
					if (($transaction_status == 'CREATED') || ($transaction_status == 'COMPLETED') || ($transaction_status == 'PENDING')) {
						$subscription_transaction_data = [
							'subscription_id' => $subscription['subscription_id'],
							'transaction_id'  => $transaction_id,
							'amount'          => $amount
						];

						$this->addSubscriptionTransaction($subscription_transaction_data);

						$this->editSubscriptionRemainingDateNext($subscription['subscription_id'], $remaining, $trial_remaining, $date_next);
					} else {
						$subscription_transaction_data = [
							'subscription_id' => $subscription['subscription_id'],
							'transaction_id'  => $transaction_id,
							'amount'          => $amount
						];

						$this->addSubscriptionTransaction($subscription_transaction_data);
					}
				}
			}
		}
	}

	/**
	 * createPayment
	 *
	 * @param mixed $order_data
	 * @param mixed $paypal_order_data
	 * @param mixed $price
	 * @param mixed $subscription_id
	 * @param mixed $subscription_name
	 */
	public function createPayment($order_data, $paypal_order_data, $price, $subscription_id, $subscription_name) {
		$this->load->language('extension/paypal/payment/paypal');

		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
		$_config->load('paypal');

		$config_setting = $_config->get('paypal_setting');

		$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));

		$client_id = $this->config->get('payment_paypal_client_id');
		$secret = $this->config->get('payment_paypal_secret');
		$merchant_id = $this->config->get('payment_paypal_merchant_id');
		$environment = $this->config->get('payment_paypal_environment');
		$partner_id = $setting['partner'][$environment]['partner_id'];
		$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
		$transaction_method = $setting['general']['transaction_method'];

		$currency_code = $order_data['currency_code'];
		$currency_value = $this->currency->getValue($currency_code);
		$decimal_place = $setting['currency'][$currency_code]['decimal_place'];

		require_once DIR_EXTENSION . 'paypal/system/library/paypal.php';

		$paypal_info = [
			'partner_id'             => $partner_id,
			'client_id'              => $client_id,
			'secret'                 => $secret,
			'environment'            => $environment,
			'partner_attribution_id' => $partner_attribution_id
		];

		$paypal = new \Opencart\System\Library\PayPal($paypal_info);

		$token_info = [
			'grant_type' => 'client_credentials'
		];

		$paypal->setAccessToken($token_info);

		$item_info = [];

		$item_total = 0;

		$product_price = number_format($price * $currency_value, $decimal_place, '.', '');

		$item_info[] = [
			'name'        => $subscription_name,
			'quantity'    => 1,
			'unit_amount' => [
				'currency_code' => $currency_code,
				'value'         => $product_price
			]
		];

		$item_total += $product_price;

		$item_total = number_format($item_total, $decimal_place, '.', '');
		$order_total = number_format($item_total, $decimal_place, '.', '');

		$amount_info = [];

		$amount_info['currency_code'] = $currency_code;
		$amount_info['value'] = $order_total;

		$amount_info['breakdown']['item_total'] = [
			'currency_code' => $currency_code,
			'value'         => $item_total
		];

		$paypal_order_info = [];

		$paypal_order_info['intent'] = strtoupper($transaction_method);
		$paypal_order_info['purchase_units'][0]['reference_id'] = 'default';
		$paypal_order_info['purchase_units'][0]['items'] = $item_info;
		$paypal_order_info['purchase_units'][0]['amount'] = $amount_info;

		$paypal_order_info['purchase_units'][0]['description'] = 'Subscription to order ' . $order_data['order_id'];

		$shipping_preference = 'NO_SHIPPING';

		$paypal_order_info['application_context']['shipping_preference'] = $shipping_preference;

		$paypal_order_info['payment_source'][$paypal_order_data['payment_method']]['vault_id'] = $paypal_order_data['vault_id'];

		$result = $paypal->createOrder($paypal_order_info);

		$errors = [];

		if ($paypal->hasErrors()) {
			$errors = $paypal->getErrors();

			foreach ($errors as $error) {
				if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
					$error['message'] = $this->language->get('error_timeout');
				}

				$this->log($error, $error['message']);
			}
		}

		if (isset($result['id']) && isset($result['status']) && !$errors) {
			$this->log($result, 'Create Subscription Payment');

			return $result;
		}

		return false;
	}

	/**
	 * calculateSchedule
	 *
	 * @param mixed $frequency
	 * @param mixed $next_payment
	 * @param mixed $cycle
	 */
	public function calculateSchedule($frequency, $next_payment, $cycle) {
		if ($frequency == 'semi_month') {
			$day = date_format($next_payment, 'd');
			$value = 15 - $day;
			$is_even = false;

			if ($cycle % 2 == 0) {
				$is_even = true;
			}

			$odd = ($cycle + 1) / 2;
			$plus_even = ($cycle / 2) + 1;
			$minus_even = $cycle / 2;

			if ($day == 1) {
				$odd--;
				$plus_even--;
				$day = 16;
			}

			if ($day <= 15 && $is_even) {
				$next_payment->modify('+' . $value . ' day');
				$next_payment->modify('+' . $minus_even . ' month');
			} elseif ($day <= 15) {
				$next_payment->modify('first day of this month');
				$next_payment->modify('+' . $odd . ' month');
			} elseif ($day > 15 && $is_even) {
				$next_payment->modify('first day of this month');
				$next_payment->modify('+' . $plus_even . ' month');
			} elseif ($day > 15) {
				$next_payment->modify('+' . $value . ' day');
				$next_payment->modify('+' . $odd . ' month');
			}
		} else {
			$next_payment->modify('+' . $cycle . ' ' . $frequency);
		}

		return $next_payment;
	}

	/**
	 * getAgreeStatus
	 *
	 * @return bool
	 */
	public function getAgreeStatus(): bool {
		$agree_status = true;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `status` = '1' AND (`iso_code_2` = 'CU' OR `iso_code_2` = 'IR' OR `iso_code_2` = 'SY' OR `iso_code_2` = 'KP')");

		if ($query->rows) {
			$agree_status = false;
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE `country_id` = '220' AND `status` = '1' AND (`code` = '43' OR `code` = '14' OR `code` = '09')");

		if ($query->rows) {
			$agree_status = false;
		}

		return $agree_status;
	}

	/**
	 * Log
	 *
	 * @param array  $data
	 * @param string $title
	 *
	 * @return void
	 */
	public function log(array $data = [], string $title = ''): void {
		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
		$_config->load('paypal');

		$config_setting = $_config->get('paypal_setting');

		$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));

		if ($setting['general']['debug']) {
			$log = new \Opencart\System\Library\Log('paypal.log');
			$log->write('PayPal debug (' . $title . '): ' . json_encode($data));
		}
	}

	/**
	 * Update
	 *
	 * @return void
	 */
	public function update(): void {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "paypal_checkout_integration_order`");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "paypal_checkout_integration_order` (`order_id` INT(11) NOT NULL, `transaction_id` VARCHAR(20) NOT NULL, `transaction_status` VARCHAR(20) NULL, `payment_method` VARCHAR(20) NULL, `vault_id` VARCHAR(50) NULL, `vault_customer_id` VARCHAR(50) NULL, `environment` VARCHAR(20) NULL, PRIMARY KEY (`order_id`, `transaction_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `code` = 'paypal_order_info'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `code` = 'paypal_header'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `code` = 'paypal_extension_get_extensions_by_type'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `code` = 'paypal_extension_get_extension_by_code'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `code` = 'paypal_order_delete_order'");

		if (VERSION >= '4.0.2.0') {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'paypal_order_info', `description` = '', `trigger` = 'admin/view/sale/order_info/before', `action` = 'extension/paypal/payment/paypal.order_info_before', `status` = '1', `sort_order` = '1'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'paypal_header', `description` = '', `trigger` = 'catalog/controller/common/header/before', `action` = 'extension/paypal/payment/paypal.header_before', `status` = '1', `sort_order` = '2'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'paypal_order_delete_order', `description` = '', `trigger` = 'catalog/model/checkout/order/deleteOrder/before', `action` = 'extension/paypal/payment/paypal.order_delete_order_before', `status` = '1', `sort_order` = '3'");
		} elseif (VERSION >= '4.0.1.0') {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'paypal_order_info', `description` = '', `trigger` = 'admin/view/sale/order_info/before', `action` = 'extension/paypal/payment/paypal|order_info_before', `status` = '1', `sort_order` = '1'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'paypal_header', `description` = '', `trigger` = 'catalog/controller/common/header/before', `action` = 'extension/paypal/payment/paypal|header_before', `status` = '1', `sort_order` = '2'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'paypal_extension_get_extensions_by_type', `description` = '', `trigger` = 'catalog/model/setting/extension/getExtensionsByType/after', `action` = 'extension/paypal/payment/paypal|extension_get_extensions_by_type_after', `status` = '1', `sort_order` = '3'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'paypal_extension_get_extension_by_code', `description` = '', `trigger` = 'catalog/model/setting/extension/getExtensionByCode/after', `action` = 'extension/paypal/payment/paypal|extension_get_extension_by_code_after', `status` = '1', `sort_order` = '4'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'paypal_order_delete_order', `description` = '', `trigger` = 'catalog/model/checkout/order/deleteOrder/before', `action` = 'extension/paypal/payment/paypal|order_delete_order_before', `status` = '1', `sort_order` = '5'");
		} else {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'paypal_order_info', `description` = '', `trigger` = 'admin/view/sale/order_info/before', `action` = 'extension/paypal/payment/paypal|order_info_before', `status` = '1', `sort_order` = '1'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'paypal_header', `description` = '', `trigger` = 'catalog/controller/common/header/before', `action` = 'extension/paypal/payment/paypal|header_before', `status` = '1', `sort_order` = '2'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'paypal_extension_get_extensions_by_type', `description` = '', `trigger` = 'catalog/model/setting/extension/getExtensionsByType/after', `action` = 'extension/paypal/payment/paypal|extension_get_extensions_by_type_after', `status` = '1', `sort_order` = '3'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'paypal_extension_get_extension_by_code', `description` = '', `trigger` = 'catalog/model/setting/extension/getExtensionByCode/after', `action` = 'extension/paypal/payment/paypal|extension_get_extension_by_code_after', `status` = '1', `sort_order` = '4'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'paypal_order_delete_order', `description` = '', `trigger` = 'catalog/model/checkout/order/deleteOrder/before', `action` = 'extension/paypal/payment/paypal|order_delete_order_before', `status` = '1', `sort_order` = '5'");
		}

		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
		$_config->load('paypal');

		$config_setting = $_config->get('paypal_setting');

		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' AND `code` = 'paypal_version'");

		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'paypal_version', `key` = 'paypal_version', `value` = '" . $this->db->escape($config_setting['version']) . "'");

		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = 'Lax', `serialized` = '0'  WHERE `code` = 'config' AND `key` = 'config_session_samesite' AND `store_id` = '0'");
	}

	private function isApple(): bool {
		if (!empty($this->request->server['HTTP_USER_AGENT'])) {
			$user_agent = $this->request->server['HTTP_USER_AGENT'];

			$apple_agents = ['ipod', 'iphone', 'ipad'];

			foreach ($apple_agents as $apple_agent) {
				if (stripos($user_agent, $apple_agent)) {
					return true;
				}
			}
		}

		return false;
	}
}
