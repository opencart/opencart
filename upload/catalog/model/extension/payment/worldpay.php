<?php

class ModelExtensionPaymentWorldpay extends Model {

	public function getMethod($address, $total) {
		$this->load->language('extension/payment/worldpay');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('worldpay_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('worldpay_total') > 0 && $this->config->get('worldpay_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('worldpay_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code' => 'worldpay',
				'title' => $this->language->get('text_title'),
				'terms' => '',
				'sort_order' => $this->config->get('worldpay_sort_order')
			);
		}

		return $method_data;
	}

	public function getCards($customer_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "worldpay_card WHERE customer_id = '" . (int)$customer_id . "'");

		$card_data = array();

		$this->load->model('account/address');

		foreach ($query->rows as $row) {

			$card_data[] = array(
				'card_id' => $row['card_id'],
				'customer_id' => $row['customer_id'],
				'token' => $row['token'],
				'digits' => $row['digits'],
				'expiry' => $row['expiry'],
				'type' => $row['type'],
			);
		}
		return $card_data;
	}

	public function addCard($order_id, $card_data) {
		$this->db->query("INSERT into " . DB_PREFIX . "worldpay_card SET customer_id = '" . $this->db->escape($card_data['customer_id']) . "', order_id = '" . $this->db->escape($order_id) . "', digits = '" . $this->db->escape($card_data['Last4Digits']) . "', expiry = '" . $this->db->escape($card_data['ExpiryDate']) . "', type = '" . $this->db->escape($card_data['CardType']) . "', token = '" . $this->db->escape($card_data['Token']) . "'");
	}

	public function deleteCard($token) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "worldpay_card WHERE customer_id = '" . $this->customer->isLogged() . "' AND token = '" . $this->db->escape($token) . "'");

		if ($this->db->countAffected() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function addOrder($order_info, $order_code) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "worldpay_order` SET `order_id` = '" . (int)$order_info['order_id'] . "', `order_code` = '" . $this->db->escape($order_code) . "', `date_added` = now(), `date_modified` = now(), `currency_code` = '" . $this->db->escape($order_info['currency_code']) . "', `total` = '" . $this->currency->format($order_info['total'], $order_info['currency_code'], false, false) . "'");

		return $this->db->getLastId();
	}

	public function getOrder($order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "worldpay_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($qry->num_rows) {
			$order = $qry->row;
			$order['transactions'] = $this->getTransactions($order['worldpay_order_id']);

			return $order;
		} else {
			return false;
		}
	}

	public function addTransaction($worldpay_order_id, $type, $order_info) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "worldpay_order_transaction` SET `worldpay_order_id` = '" . (int)$worldpay_order_id . "', `date_added` = now(), `type` = '" . $this->db->escape($type) . "', `amount` = '" . $this->currency->format($order_info['total'], $order_info['currency_code'], false, false) . "'");
	}

	public function getTransactions($worldpay_order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "worldpay_order_transaction` WHERE `worldpay_order_id` = '" . (int)$worldpay_order_id . "'");

		if ($qry->num_rows) {
			return $qry->rows;
		} else {
			return false;
		}
	}

	public function recurringPayment($item, $order_id_rand, $token) {

		$this->load->model('checkout/recurring');
		$this->load->model('extension/payment/worldpay');
		//trial information
		if ($item['recurring']['trial'] == 1) {
			$price = $item['recurring']['trial_price'];
			$trial_amt = $this->currency->format($this->tax->calculate($item['recurring']['trial_price'], $item['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], false, false) * $item['quantity'] . ' ' . $this->session->data['currency'];
			$trial_text = sprintf($this->language->get('text_trial'), $trial_amt, $item['recurring']['trial_cycle'], $item['recurring']['trial_frequency'], $item['recurring']['trial_duration']);
		} else {
			$price = $item['recurring']['price'];
			$trial_text = '';
		}

		$recurring_amt = $this->currency->format($this->tax->calculate($item['recurring']['price'], $item['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], false, false) * $item['quantity'] . ' ' . $this->session->data['currency'];
		$recurring_description = $trial_text . sprintf($this->language->get('text_recurring'), $recurring_amt, $item['recurring']['cycle'], $item['recurring']['frequency']);

		if ($item['recurring']['duration'] > 0) {
			$recurring_description .= sprintf($this->language->get('text_length'), $item['recurring']['duration']);
		}

		$order_recurring_id = $this->model_checkout_recurring->create($item, $this->session->data['order_id'], $recurring_description);
		$this->model_checkout_recurring->addReference($order_recurring_id, $order_id_rand);

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$order = array(
			"token" => $token,
			"orderType" => 'RECURRING',
			"amount" => (int)($price * 100),
			"currencyCode" => $order_info['currency_code'],
			"name" => $order_info['firstname'] . ' ' . $order_info['lastname'],
			"orderDescription" => $order_info['store_name'] . ' - ' . date('Y-m-d H:i:s'),
			"customerOrderCode" => 'orderRecurring-' . $order_recurring_id
		);

		$this->model_extension_payment_worldpay->logger($order);

		$response_data = $this->model_extension_payment_worldpay->sendCurl('orders', $order);

		$this->model_extension_payment_worldpay->logger($response_data);

		$next_payment = new DateTime('now');
		$trial_end = new DateTime('now');
		$subscription_end = new DateTime('now');

		if ($item['recurring']['trial'] == 1 && $item['recurring']['trial_duration'] != 0) {
			$next_payment = $this->calculateSchedule($item['recurring']['trial_frequency'], $next_payment, $item['recurring']['trial_cycle']);
			$trial_end = $this->calculateSchedule($item['recurring']['trial_frequency'], $trial_end, $item['recurring']['trial_cycle'] * $item['recurring']['trial_duration']);
		} elseif ($item['recurring']['trial'] == 1) {
			$next_payment = $this->calculateSchedule($item['recurring']['trial_frequency'], $next_payment, $item['recurring']['trial_cycle']);
			$trial_end = new DateTime('0000-00-00');
		}

		if ($trial_end > $subscription_end && $item['recurring']['duration'] != 0) {
			$subscription_end = new DateTime(date_format($trial_end, 'Y-m-d H:i:s'));
			$subscription_end = $this->calculateSchedule($item['recurring']['frequency'], $subscription_end, $item['recurring']['cycle'] * $item['recurring']['duration']);
		} elseif ($trial_end == $subscription_end && $item['recurring']['duration'] != 0) {
			$next_payment = $this->calculateSchedule($item['recurring']['frequency'], $next_payment, $item['recurring']['cycle']);
			$subscription_end = $this->calculateSchedule($item['recurring']['frequency'], $subscription_end, $item['recurring']['cycle'] * $item['recurring']['duration']);
		} elseif ($trial_end > $subscription_end && $item['recurring']['duration'] == 0) {
			$subscription_end = new DateTime('0000-00-00');
		} elseif ($trial_end == $subscription_end && $item['recurring']['duration'] == 0) {
			$next_payment = $this->calculateSchedule($item['recurring']['frequency'], $next_payment, $item['recurring']['cycle']);
			$subscription_end = new DateTime('0000-00-00');
		}

		if (isset($response_data->paymentStatus) && $response_data->paymentStatus == 'SUCCESS') {
			$this->addRecurringOrder($order_info, $response_data->orderCode, $token, $price, $order_recurring_id, date_format($trial_end, 'Y-m-d H:i:s'), date_format($subscription_end, 'Y-m-d H:i:s'));

			$this->updateRecurringOrder($order_recurring_id, date_format($next_payment, 'Y-m-d H:i:s'));

			$this->addProfileTransaction($order_recurring_id, $response_data->orderCode, $price, 1);
		} else {
			$this->addProfileTransaction($order_recurring_id, '', $price, 4);
		}
	}

	public function cronPayment() {

		$this->load->model('account/order');
		$this->load->model('checkout/order');
		$profiles = $this->getProfiles();
		$cron_data = array();
		$i = 1;
		foreach ($profiles as $profile) {
			$recurring_order = $this->getRecurringOrder($profile['order_recurring_id']);

			$today = new DateTime('now');
			$unlimited = new DateTime('0000-00-00');
			$next_payment = new DateTime($recurring_order['next_payment']);
			$trial_end = new DateTime($recurring_order['trial_end']);
			$subscription_end = new DateTime($recurring_order['subscription_end']);

			$order_info = $this->model_checkout_order->getOrder($profile['order_id']);

			if (($today > $next_payment) && ($trial_end > $today || $trial_end == $unlimited)) {
				$price = $this->currency->format($profile['trial_price'], $order_info['currency_code'], false, false);
				$frequency = $profile['trial_frequency'];
				$cycle = $profile['trial_cycle'];
			} elseif (($today > $next_payment) && ($subscription_end > $today || $subscription_end == $unlimited)) {
				$price = $this->currency->format($profile['recurring_price'], $order_info['currency_code'], false, false);
				$frequency = $profile['recurring_frequency'];
				$cycle = $profile['recurring_cycle'];
			} else {
				continue;
			}

			$order = array(
				"token" => $recurring_order['token'],
				"orderType" => 'RECURRING',
				"amount" => (int)($price * 100),
				"currencyCode" => $order_info['currency_code'],
				"name" => $order_info['firstname'] . ' ' . $order_info['lastname'],
				"orderDescription" => $order_info['store_name'] . ' - ' . date('Y-m-d H:i:s'),
				"customerOrderCode" => 'orderRecurring-' . $profile['order_recurring_id'] . '-repeat-' . $i++
			);

			$this->model_extension_payment_worldpay->logger($order);

			$response_data = $this->model_extension_payment_worldpay->sendCurl('orders', $order);

			$this->model_extension_payment_worldpay->logger($response_data);

			$cron_data[] = $response_data;

			if (isset($response_data->paymentStatus) && $response_data->paymentStatus == 'SUCCESS') {
				$this->addProfileTransaction($profile['order_recurring_id'], $response_data->orderCode, $price, 1);
				$next_payment = $this->calculateSchedule($frequency, $next_payment, $cycle);
				$next_payment = date_format($next_payment, 'Y-m-d H:i:s');
				$this->updateRecurringOrder($profile['order_recurring_id'], $next_payment);
			} else {
				$this->addProfileTransaction($profile['order_recurring_id'], '', $price, 4);
			}
		}
		$log = new Log('worldpay_recurring_orders.log');
		$log->write(print_r($cron_data, 1));
		return $cron_data;
	}

	private function calculateSchedule($frequency, $next_payment, $cycle) {
		if ($frequency == 'semi_month') {
			$day = date_format($next_payment, 'd');
			$value = 15 - $day;
			$isEven = false;
			if ($cycle % 2 == 0) {
				$isEven = true;
			}

			$odd = ($cycle + 1) / 2;
			$plus_even = ($cycle / 2) + 1;
			$minus_even = $cycle / 2;

			if ($day == 1) {
				$odd = $odd - 1;
				$plus_even = $plus_even - 1;
				$day = 16;
			}

			if ($day <= 15 && $isEven) {
				$next_payment->modify('+' . $value . ' day');
				$next_payment->modify('+' . $minus_even . ' month');
			} elseif ($day <= 15) {
				$next_payment->modify('first day of this month');
				$next_payment->modify('+' . $odd . ' month');
			} elseif ($day > 15 && $isEven) {
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

	private function addRecurringOrder($order_info, $order_code, $token, $price, $order_recurring_id, $trial_end, $subscription_end) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "worldpay_order_recurring` SET `order_id` = '" . (int)$order_info['order_id'] . "', `order_recurring_id` = '" . (int)$order_recurring_id . "', `order_code` = '" . $this->db->escape($order_code) . "', `token` = '" . $this->db->escape($token) . "', `date_added` = now(), `date_modified` = now(), `next_payment` = now(), `trial_end` = '" . $trial_end . "', `subscription_end` = '" . $subscription_end . "', `currency_code` = '" . $this->db->escape($order_info['currency_code']) . "', `total` = '" . $this->currency->format($price, $order_info['currency_code'], false, false) . "'");
	}

	private function updateRecurringOrder($order_recurring_id, $next_payment) {
		$this->db->query("UPDATE `" . DB_PREFIX . "worldpay_order_recurring` SET `next_payment` = '" . $next_payment . "', `date_modified` = now() WHERE `order_recurring_id` = '" . (int)$order_recurring_id . "'");
	}

	private function getRecurringOrder($order_recurring_id) {
		$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "worldpay_order_recurring WHERE order_recurring_id = '" . (int)$order_recurring_id . "'");
		return $qry->row;
	}

	private function addProfileTransaction($order_recurring_id, $order_code, $price, $type) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int)$order_recurring_id . "', `date_added` = NOW(), `amount` = '" . (float)$price . "', `type` = '" . (int)$type . "', `reference` = '" . $this->db->escape($order_code) . "'");
	}

	private function getProfiles() {
		$sql = "
			SELECT `or`.order_recurring_id
			FROM `" . DB_PREFIX . "order_recurring` `or`
			JOIN `" . DB_PREFIX . "order` `o` USING(`order_id`)
			WHERE o.payment_code = 'worldpay'";

		$qry = $this->db->query($sql);

		$order_recurring = array();

		foreach ($qry->rows as $profile) {
			$order_recurring[] = $this->getProfile($profile['order_recurring_id']);
		}
		return $order_recurring;
	}

	private function getProfile($order_recurring_id) {
		$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_recurring WHERE order_recurring_id = " . (int)$order_recurring_id);
		return $qry->row;
	}

	public function getWorldpayOrder($worldpay_order_id) {
		$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "worldpay_order WHERE order_code = " . (int)$worldpay_order_id);
		return $qry->row;
	}

	public function updateCronJobRunTime() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = 'worldpay' AND `key` = 'worldpay_last_cron_job_run'");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`store_id`, `code`, `key`, `value`, `serialized`) VALUES (0, 'worldpay', 'worldpay_last_cron_job_run', NOW(), 0)");
	}

	public function sendCurl($url, $order = null) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://api.worldpay.com/v1/' . $url);
		$content_length = 0;
		if ($order) {
			$json = json_encode($order);
			$content_length = strlen($json);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt(
				$curl, CURLOPT_HTTPHEADER, array(
			"Authorization: " . $this->config->get('worldpay_service_key'),
			"Content-Type: application/json",
			"Content-Length: " . $content_length
				)
		);

		$result = json_decode(curl_exec($curl));
		curl_close($curl);
		return $result;
	}

	public function logger($data) {
		if ($this->config->get('worldpay_debug')) {
			$log = new Log('worldpay_debug.log');
			$backtrace = debug_backtrace();
			$log->write($backtrace[6]['class'] . '::' . $backtrace[6]['function'] . ' Data:  ' . print_r($data, 1));
		}
	}

	public function recurringPayments() {
		/*
		 * Used by the checkout to state the module
		 * supports recurring profiles.
		 */
		return true;
	}

}
