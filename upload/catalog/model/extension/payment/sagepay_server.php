<?php
class ModelExtensionPaymentSagepayServer extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/sagepay_server');

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE geo_zone_id = '" . (int)$this->config->get('payment_sagepay_server_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('payment_sagepay_server_total') > 0 && $this->config->get('payment_sagepay_server_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payment_sagepay_server_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code' => 'sagepay_server',
				'title' => $this->language->get('text_title'),
				'terms' => '',
				'sort_order' => $this->config->get('payment_sagepay_server_sort_order')
			);
		}

		return $method_data;
	}

	public function getCards($customer_id) {

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sagepay_server_card` WHERE customer_id = '" . (int)$customer_id . "'");

		$card_data = array();

		$this->load->model('account/address');

		foreach ($query->rows as $row) {

			$card_data[] = array(
				'card_id' => $row['card_id'],
				'customer_id' => $row['customer_id'],
				'token' => $row['token'],
				'digits' => '**** ' . $row['digits'],
				'expiry' => $row['expiry'],
				'type' => $row['type'],
			);
		}
		return $card_data;
	}

	public function getCard($card_id, $token) {
		$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "sagepay_server_card WHERE (card_id = '" . $this->db->escape($card_id) . "' OR token = '" . $this->db->escape($token) . "') AND customer_id = '" . (int)$this->customer->getId() . "'");

		if ($qry->num_rows) {
			return $qry->row;
		} else {
			return false;
		}
	}

	public function addCard($data) {
		$this->db->query("INSERT into `" . DB_PREFIX . "sagepay_server_card` SET customer_id = '" . $this->db->escape((string)$data['customer_id']) . "', token = '" . $this->db->escape((string)$data['Token']) . "', digits = '" . $this->db->escape((string)$data['Last4Digits']) . "', expiry = '" . $this->db->escape((string)$data['ExpiryDate']) . "', type = '" . $this->db->escape((string)$data['CardType']) . "'");
	}

	public function deleteCard($card_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "sagepay_server_card WHERE card_id = '" . (int)$card_id . "'");
	}

	public function addOrder($order_info) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "sagepay_server_order` WHERE `order_id` = '" . (int)$order_info['order_id'] . "'");

		$this->db->query("INSERT INTO `" . DB_PREFIX . "sagepay_server_order` SET `order_id` = '" . (int)$order_info['order_id'] . "', `customer_id` = '" . (int)$this->customer->getId() . "', `VPSTxId` = '" . $this->db->escape($order_info['VPSTxId']) . "',  `VendorTxCode` = '" . $this->db->escape($order_info['VendorTxCode']) . "', `SecurityKey` = '" . $this->db->escape($order_info['SecurityKey']) . "', `date_added` = now(), `date_modified` = now(), `currency_code` = '" . $this->db->escape($order_info['currency_code']) . "', `total` = '" . $this->currency->format($order_info['total'], $order_info['currency_code'], false, false) . "'");
	}

	public function getOrder($order_id, $vpstx_id = null) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sagepay_server_order` WHERE `order_id` = '" . (int)$order_id . "' OR `VPSTxId` = '" . $this->db->escape($vpstx_id) . "' LIMIT 1");

		if ($qry->num_rows) {
			$order = $qry->row;
			$order['transactions'] = $this->getTransactions($order['sagepay_server_order_id']);

			return $order;
		} else {
			return false;
		}
	}

	public function updateOrder($order_info, $vps_txn_id, $tx_auth_no) {
		$this->db->query("UPDATE `" . DB_PREFIX . "sagepay_server_order` SET `VPSTxId` = '" . $this->db->escape($vps_txn_id) . "', `TxAuthNo` = '" . $this->db->escape($tx_auth_no) . "' WHERE `order_id` = '" . (int)$order_info['order_id'] . "'");
	}

	public function deleteOrder($order_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "sagepay_server_order` WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_recurring` WHERE order_id = '" . (int)$order_id . "'");
	}

	public function addTransaction($sagepay_server_order_id, $type, $order_info) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "sagepay_server_order_transaction` SET `sagepay_server_order_id` = '" . (int)$sagepay_server_order_id . "', `date_added` = now(), `type` = '" . $this->db->escape($type) . "', `amount` = '" . $this->currency->format($order_info['total'], $order_info['currency_code'], false, false) . "'");
	}

	private function getTransactions($sagepay_server_order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sagepay_server_order_transaction` WHERE `sagepay_server_order_id` = '" . (int)$sagepay_server_order_id . "'");

		if ($qry->num_rows) {
			return $qry->rows;
		} else {
			return false;
		}
	}

	public function getRecurringOrders($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_recurring` WHERE order_id = '" . (int)$order_id . "'");
		return $query->rows;
	}

	public function addRecurringPayment($item, $vendor_tx_code) {

		$this->load->model('checkout/recurring');
		$this->load->language('extension/payment/sagepay_server');

		//trial information
		if ($item['recurring']['trial'] == 1) {
			$trial_amt = $this->currency->format($this->tax->calculate($item['recurring']['trial_price'], $item['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], false, false) * $item['quantity'] . ' ' . $this->session->data['currency'];
			$trial_text = sprintf($this->language->get('text_trial'), $trial_amt, $item['recurring']['trial_cycle'], $item['recurring']['trial_frequency'], $item['recurring']['trial_duration']);
		} else {
			$trial_text = '';
		}

		$recurring_amt = $this->currency->format($this->tax->calculate($item['recurring']['price'], $item['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], false, false) * $item['quantity'] . ' ' . $this->session->data['currency'];
		$recurring_description = $trial_text . sprintf($this->language->get('text_recurring'), $recurring_amt, $item['recurring']['cycle'], $item['recurring']['frequency']);

		if ($item['recurring']['duration'] > 0) {
			$recurring_description .= sprintf($this->language->get('text_length'), $item['recurring']['duration']);
		}

		//create new recurring and set to pending status as no payment has been made yet.
		$recurring_id = $this->model_checkout_recurring->addRecurring($this->session->data['order_id'], $recurring_description, $item);

		$this->model_checkout_recurring->editReference($recurring_id, $vendor_tx_code);
	}

	public function updateRecurringPayment($item, $order_details) {

		$this->load->model('checkout/recurring');

		$order_info = $this->model_checkout_order->getOrder($order_details['order_id']);

		//trial information
		if ($item['trial'] == 1) {
			$price = $this->currency->format($item['trial_price'], $this->session->data['currency'], false, false);
		} else {
			$price = $this->currency->format($item['recurring_price'], $this->session->data['currency'], false, false);
		}

		$response_data = $this->setPaymentData($order_info, $order_details, $price, $item['order_recurring_id'], $item['recurring_name']);

		$next_payment = new DateTime('now');
		$trial_end = new DateTime('now');
		$subscription_end = new DateTime('now');

		if ($item['trial'] == 1 && $item['trial_duration'] != 0) {
			$next_payment = $this->calculateSchedule($item['trial_frequency'], $next_payment, $item['trial_cycle']);
			$trial_end = $this->calculateSchedule($item['trial_frequency'], $trial_end, $item['trial_cycle'] * $item['trial_duration']);
		} elseif ($item['trial'] == 1) {
			$next_payment = $this->calculateSchedule($item['trial_frequency'], $next_payment, $item['trial_cycle']);
			$trial_end = new DateTime('0000-00-00');
		}

		if ($trial_end > $subscription_end && $item['recurring_duration'] != 0) {
			$subscription_end = new DateTime(date_format($trial_end, 'Y-m-d H:i:s'));
			$subscription_end = $this->calculateSchedule($item['recurring_frequency'], $subscription_end, $item['recurring_cycle'] * $item['recurring_duration']);
		} elseif ($trial_end == $subscription_end && $item['recurring_duration'] != 0) {
			$next_payment = $this->calculateSchedule($item['recurring_frequency'], $next_payment, $item['recurring_cycle']);
			$subscription_end = $this->calculateSchedule($item['recurring_frequency'], $subscription_end, $item['recurring_cycle'] * $item['recurring_duration']);
		} elseif ($trial_end > $subscription_end && $item['recurring_duration'] == 0) {
			$subscription_end = new DateTime('0000-00-00');
		} elseif ($trial_end == $subscription_end && $item['recurring_duration'] == 0) {
			$next_payment = $this->calculateSchedule($item['recurring_frequency'], $next_payment, $item['recurring_cycle']);
			$subscription_end = new DateTime('0000-00-00');
		}

		$this->addRecurringOrder($order_details['order_id'], $response_data, $item['order_recurring_id'], date_format($trial_end, 'Y-m-d H:i:s'), date_format($subscription_end, 'Y-m-d H:i:s'));

		if ($response_data['Status'] == 'OK') {
			$this->updateRecurringOrder($item['order_recurring_id'], date_format($next_payment, 'Y-m-d H:i:s'));

			$this->addRecurringTransaction($item['order_recurring_id'], $response_data, 1);
		} else {
			$this->addRecurringTransaction($item['order_recurring_id'], $response_data, 4);
		}
	}

	private function setPaymentData($order_info, $sagepay_order_info, $price, $order_recurring_id, $recurring_name, $i = null) {
		if ($this->config->get('payment_sagepay_server_test') == 'live') {
			$url = 'https://live.sagepay.com/gateway/service/repeat.vsp';
			$payment_data['VPSProtocol'] = '3.00';
		} elseif ($this->config->get('payment_sagepay_server_test') == 'test') {
			$url = 'https://test.sagepay.com/gateway/service/repeat.vsp';
			$payment_data['VPSProtocol'] = '3.00';
		} elseif ($this->config->get('payment_sagepay_server_test') == 'sim') {
			$url = 'https://test.sagepay.com/Simulator/VSPServerGateway.asp?Service=VendorRepeatTx';
			$payment_data['VPSProtocol'] = '2.23';
		}

		$payment_data['TxType'] = 'REPEAT';
		$payment_data['Vendor'] = $this->config->get('payment_sagepay_server_vendor');
		$payment_data['VendorTxCode'] = $order_recurring_id . 'RSD' . strftime("%Y%m%d%H%M%S") . mt_rand(1, 999);
		$payment_data['Amount'] = $this->currency->format($price, $this->session->data['currency'], false, false);
		$payment_data['Currency'] = $this->session->data['currency'];
		$payment_data['Description'] = substr($recurring_name, 0, 100);
		$payment_data['RelatedVPSTxId'] = trim($sagepay_order_info['VPSTxId'], '{}');
		$payment_data['RelatedVendorTxCode'] = $sagepay_order_info['VendorTxCode'];
		$payment_data['RelatedSecurityKey'] = $sagepay_order_info['SecurityKey'];
		$payment_data['RelatedTxAuthNo'] = $sagepay_order_info['TxAuthNo'];

		if (!empty($order_info['shipping_lastname'])) {
			$payment_data['DeliverySurname'] = substr($order_info['shipping_lastname'], 0, 20);
			$payment_data['DeliveryFirstnames'] = substr($order_info['shipping_firstname'], 0, 20);
			$payment_data['DeliveryAddress1'] = substr($order_info['shipping_address_1'], 0, 100);

			if ($order_info['shipping_address_2']) {
				$payment_data['DeliveryAddress2'] = $order_info['shipping_address_2'];
			}

			$payment_data['DeliveryCity'] = substr($order_info['shipping_city'], 0, 40);
			$payment_data['DeliveryPostCode'] = substr($order_info['shipping_postcode'], 0, 10);
			$payment_data['DeliveryCountry'] = $order_info['shipping_iso_code_2'];

			if ($order_info['shipping_iso_code_2'] == 'US') {
				$payment_data['DeliveryState'] = $order_info['shipping_zone_code'];
			}

			$payment_data['CustomerName'] = substr($order_info['firstname'] . ' ' . $order_info['lastname'], 0, 100);
			$payment_data['DeliveryPhone'] = substr($order_info['telephone'], 0, 20);
		} else {
			$payment_data['DeliveryFirstnames'] = $order_info['payment_firstname'];
			$payment_data['DeliverySurname'] = $order_info['payment_lastname'];
			$payment_data['DeliveryAddress1'] = $order_info['payment_address_1'];

			if ($order_info['payment_address_2']) {
				$payment_data['DeliveryAddress2'] = $order_info['payment_address_2'];
			}

			$payment_data['DeliveryCity'] = $order_info['payment_city'];
			$payment_data['DeliveryPostCode'] = $order_info['payment_postcode'];
			$payment_data['DeliveryCountry'] = $order_info['payment_iso_code_2'];

			if ($order_info['payment_iso_code_2'] == 'US') {
				$payment_data['DeliveryState'] = $order_info['payment_zone_code'];
			}

			$payment_data['DeliveryPhone'] = $order_info['telephone'];
		}
		$response_data = $this->sendCurl($url, $payment_data, $i);
		$response_data['VendorTxCode'] = $payment_data['VendorTxCode'];
		$response_data['Amount'] = $payment_data['Amount'];
		$response_data['Currency'] = $payment_data['Currency'];

		return $response_data;
	}

	public function cronPayment() {

		$this->load->model('account/order');
		$recurrings = $this->getProfiles();
		$cron_data = array();
		$i = 0;

		foreach ($recurrings as $recurring) {

			$recurring_order = $this->getRecurringOrder($recurring['order_recurring_id']);

			$today = new DateTime('now');
			$unlimited = new DateTime('0000-00-00');
			$next_payment = new DateTime($recurring_order['next_payment']);
			$trial_end = new DateTime($recurring_order['trial_end']);
			$subscription_end = new DateTime($recurring_order['subscription_end']);

			$order_info = $this->model_account_order->getOrder($recurring['order_id']);

			if (($today > $next_payment) && ($trial_end > $today || $trial_end == $unlimited)) {
				$price = $this->currency->format($recurring['trial_price'], $order_info['currency_code'], false, false);
				$frequency = $recurring['trial_frequency'];
				$cycle = $recurring['trial_cycle'];
			} elseif (($today > $next_payment) && ($subscription_end > $today || $subscription_end == $unlimited)) {
				$price = $this->currency->format($recurring['recurring_price'], $order_info['currency_code'], false, false);
				$frequency = $recurring['recurring_frequency'];
				$cycle = $recurring['recurring_cycle'];
			} else {
				continue;
			}

			$sagepay_order_info = $this->getOrder($recurring['order_id']);

			$response_data = $this->setPaymentData($order_info, $sagepay_order_info, $price, $recurring['order_recurring_id'], $recurring['recurring_name'], $i);

			$cron_data[] = $response_data;

			if ($response_data['RepeatResponseData_' . $i++]['Status'] == 'OK') {
				$this->addRecurringTransaction($recurring['order_recurring_id'], $response_data, 1);
				$next_payment = $this->calculateSchedule($frequency, $next_payment, $cycle);
				$next_payment = date_format($next_payment, 'Y-m-d H:i:s');
				$this->updateRecurringOrder($recurring['order_recurring_id'], $next_payment);
			} else {
				$this->addRecurringTransaction($recurring['order_recurring_id'], $response_data, 4);
			}
		}
		$log = new Log('sagepay_server_recurring_orders.log');
		$log->write(print_r($cron_data, 1));
		return $cron_data;
	}

	private function calculateSchedule($frequency, $next_payment, $cycle) {
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
				$odd = $odd - 1;
				$plus_even = $plus_even - 1;
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

	private function addRecurringOrder($order_id, $response_data, $order_recurring_id, $trial_end, $subscription_end) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "sagepay_server_order_recurring` SET `order_id` = '" . (int)$order_id . "', `order_recurring_id` = '" . (int)$order_recurring_id . "', `VPSTxId` = '" . $this->db->escape($response_data['VPSTxId']) . "', `VendorTxCode` = '" . $this->db->escape($response_data['VendorTxCode']) . "', `SecurityKey` = '" . $this->db->escape($response_data['SecurityKey']) . "', `TxAuthNo` = '" . $this->db->escape($response_data['TxAuthNo']) . "', `date_added` = now(), `date_modified` = now(), `next_payment` = now(), `trial_end` = '" . $trial_end . "', `subscription_end` = '" . $subscription_end . "', `currency_code` = '" . $this->db->escape($response_data['Currency']) . "', `total` = '" . $this->currency->format($response_data['Amount'], $response_data['Currency'], false, false) . "'");
	}

	private function updateRecurringOrder($order_recurring_id, $next_payment) {
		$this->db->query("UPDATE `" . DB_PREFIX . "sagepay_server_order_recurring` SET `next_payment` = '" . $next_payment . "', `date_modified` = now() WHERE `order_recurring_id` = '" . (int)$order_recurring_id . "'");
	}

	private function getRecurringOrder($order_recurring_id) {
		$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "sagepay_server_order_recurring WHERE order_recurring_id = '" . (int)$order_recurring_id . "'");
		return $qry->row;
	}

	private function addRecurringTransaction($order_recurring_id, $response_data, $type) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int)$order_recurring_id . "', `date_added` = NOW(), `amount` = '" . (float)$response_data['Amount'] . "', `type` = '" . (int)$type . "', `reference` = '" . $this->db->escape($response_data['VendorTxCode']) . "'");
	}

	private function getProfiles() {

		$sql = "
			SELECT `or`.order_recurring_id
			FROM `" . DB_PREFIX . "order_recurring` `or`
			JOIN `" . DB_PREFIX . "order` `o` USING(`order_id`)
			WHERE o.payment_code = 'sagepay_server'";

		$qry = $this->db->query($sql);

		$order_recurring = array();

		foreach ($qry->rows as $recurring) {
			$order_recurring[] = $this->getProfile($recurring['order_recurring_id']);
		}
		return $order_recurring;
	}

	private function getProfile($order_recurring_id) {
		$qry = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_recurring WHERE order_recurring_id = " . (int)$order_recurring_id);
		return $qry->row;
	}

	public function updateCronJobRunTime() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = 'sagepay_server' AND `key` = 'payment_sagepay_server_last_cron_job_run'");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`store_id`, `code`, `key`, `value`, `serialized`) VALUES (0, 'sagepay_server', 'payment_sagepay_server_last_cron_job_run', NOW(), 0)");
	}

	public function sendCurl($url, $payment_data, $i = null) {
		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($payment_data));

		$response = curl_exec($curl);

		curl_close($curl);

		$response_info = explode(chr(10), $response);

		foreach ($response_info as $string) {
			if (strpos($string, '=') && isset($i)) {
				$parts = explode('=', $string, 2);
				$data['RepeatResponseData_' . $i][trim($parts[0])] = trim($parts[1]);
			} elseif (strpos($string, '=')) {
				$parts = explode('=', $string, 2);
				$data[trim($parts[0])] = trim($parts[1]);
			}
		}
		return $data;
	}

	public function logger($title, $data) {
		if ($this->config->get('payment_sagepay_server_debug')) {
			$log = new Log('sagepay_server.log');
			$backtrace = debug_backtrace();
			$log->write($backtrace[6]['class'] . '::' . $backtrace[6]['function'] . ' - ' . $title . ': ' . print_r($data, 1));
		}
	}

	public function recurringPayments() {
		/*
		 * Used by the checkout to state the module
		 * supports recurring recurrings.
		 */
		return true;
	}
}
