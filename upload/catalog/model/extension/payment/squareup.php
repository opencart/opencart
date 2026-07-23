<?php

class ModelExtensionPaymentSquareup extends Model {
	const RECURRING_ACTIVE = 1;
//  const RECURRING_INACTIVE = 2;
	const RECURRING_CANCELLED = 3;
	const RECURRING_SUSPENDED = 4;
	const RECURRING_EXPIRED = 5;
//  const RECURRING_PENDING = 6;
    
//  const TRANSACTION_DATE_ADDED = 0;
	const TRANSACTION_PAYMENT = 1;
//	const TRANSACTION_OUTSTANDING_PAYMENT = 2;
//	const TRANSACTION_SKIPPED = 3;
	const TRANSACTION_FAILED = 4;
//	const TRANSACTION_CANCELLED = 5;
//	const TRANSACTION_SUSPENDED = 6;
//	const TRANSACTION_SUSPENDED_FAILED = 7;
//	const TRANSACTION_SUSPENDED = 8;
//	const TRANSACTION_EXPIRED = 9;

	public function getMethod($address, $total) {
		$geo_zone_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_squareup_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		$squareup_display_name = $this->config->get('payment_squareup_display_name');

		$this->load->language('extension/payment/squareup');

		if (!empty($squareup_display_name[$this->config->get('config_language_id')])) {
			$title = $squareup_display_name[$this->config->get('config_language_id')];
		} else {
			$title = $this->language->get('text_default_squareup_name');
		}

		$status = true;

		$minimum_total = (float)$this->config->get('payment_squareup_total');

		$squareup_geo_zone_id = $this->config->get('payment_squareup_geo_zone_id');

		if ($minimum_total > 0 && $minimum_total > $total) {
			$status = false;
		} else if (empty($squareup_geo_zone_id)) {
			$status = true;
		} else if ($geo_zone_query->num_rows == 0) {
			$status = false;
		}

		if ($status) {
			if ($this->cart->hasRecurringProducts()) {
				if ($this->config->get('payment_squareup_quick_pay')) {
					$status = false;
				} else if ($this->config->get('payment_squareup_delay_capture')) {
					$status = false;
				}
			}
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'      => 'squareup',
				'title'     => $title,
				'terms'     => '',
				'sort_order' => (int)$this->config->get('payment_squareup_sort_order')
			);
		}

		return $method_data;
	}

	public function addPayment($payment, $merchant_id, $order_id, $user_agent, $ip) {
		$card_fingerprint = '';
		if (!empty($payment['payment']['card_details']['card']['fingerprint'])) {
			$card_fingerprint = $payment['payment']['card_details']['card']['fingerprint'];
		}
		$billing_address = array();
		if (!empty($payment['payment']['billing_address'])) {
			$billing_address = $payment['payment']['billing_address'];
		}
		$sql  = "INSERT INTO `" . DB_PREFIX . "squareup_payment` SET ";
		$sql .= "`opencart_order_id`='".(int)$order_id."', ";
		$sql .= "`payment_id`='".$this->db->escape($payment['payment']['id'])."', ";
		$sql .= "`merchant_id`='".$this->db->escape($merchant_id)."', ";
		$sql .= "`location_id`='".$this->db->escape($payment['payment']['location_id'])."', ";
		$sql .= "`order_id`='".$this->db->escape($payment['payment']['order_id'])."', ";
		$sql .= "`customer_id`='".(isset($payment['payment']['customer_id']) ? $this->db->escape($payment['payment']['customer_id']) : '')."', ";
		$sql .= "`created_at`='".$this->db->escape($payment['payment']['created_at'])."', ";
		$sql .= "`updated_at`='".$this->db->escape($payment['payment']['updated_at'])."', ";
		$sql .= "`amount`=".(int)$payment['payment']['amount_money']['amount'].", ";
		$sql .= "`currency`='".$this->db->escape($payment['payment']['amount_money']['currency'])."', ";
		$sql .= "`status`='".$this->db->escape($payment['payment']['status'])."', ";
		$sql .= "`source_type`='".$this->db->escape($payment['payment']['source_type'])."', ";
		$sql .= "`square_product`='".$this->db->escape($payment['payment']['application_details']['square_product'])."', ";
		$sql .= "`application_id`='".$this->db->escape($payment['payment']['application_details']['application_id'])."', ";
		$sql .= "`refunded_amount`=0, ";
		$sql .= "`refunded_currency`='', ";
		$sql .= "`card_fingerprint`='".$this->db->escape($card_fingerprint)."', ";
		if ($billing_address) {
			$sql .= "`first_name`='".$this->db->escape($billing_address['first_name'])."', ";
			$sql .= "`last_name`='".$this->db->escape($billing_address['last_name'])."', ";
			$sql .= "`address_line_1`='".$this->db->escape($billing_address['address_line_1'])."', ";
			$sql .= "`address_line_2`='".$this->db->escape($billing_address['address_line_2'])."', ";
			$sql .= "`address_line_3`='".$this->db->escape($billing_address['address_line_3'])."', ";
			$sql .= "`locality`='".$this->db->escape($billing_address['locality'])."', ";
			$sql .= "`sublocality`='".$this->db->escape($billing_address['sublocality'])."', ";
			$sql .= "`sublocality_2`='".$this->db->escape($billing_address['sublocality_2'])."', ";
			$sql .= "`sublocality_3`='".$this->db->escape($billing_address['sublocality_3'])."', ";
			$sql .= "`administrative_district_level_1`='".$this->db->escape($billing_address['administrative_district_level_1'])."', ";
			$sql .= "`administrative_district_level_2`='".$this->db->escape($billing_address['administrative_district_level_2'])."', ";
			$sql .= "`administrative_district_level_3`='".$this->db->escape($billing_address['administrative_district_level_3'])."', ";
			$sql .= "`postal_code`='".$this->db->escape($billing_address['postal_code'])."', ";
			$sql .= "`country`='".$this->db->escape($billing_address['country'])."', ";
		}
		$sql .= "`ip`='".$this->db->escape($ip)."', ";
		$sql .= "`user_agent`='".$this->db->escape($user_agent)."';";
		$this->db->query($sql);
	}

	public function updatePaymentCustomerId($payment_id, $customer_id) {
		$sql  = "UPDATE `".DB_PREFIX."squareup_payment` ";
		$sql .= "SET `customer_id`='".$this->db->escape($customer_id)."' ";
		$sql .= "WHERE `payment_id`='".$this->db->escape($payment_id)."';";
		$this->db->query($sql);
	}

	public function tokenExpiredEmail() {
		if (!$this->mailResendPeriodExpired('token_expired')) {
			return;
		}

		$mail = new Mail($this->config->get('config_mail_engine'));

		$mail->parameter = $this->config->get('config_mail_parameter');

		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$subject = $this->language->get('text_token_expired_subject');
		$message = $this->language->get('text_token_expired_message');

		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setReplyTo($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(strip_tags($message));
		$mail->setHtml($message);
		$mail->send();
	}

	public function tokenRevokedEmail() {
		if (!$this->mailResendPeriodExpired('token_revoked')) {
			return;
		}

		$mail = new Mail($this->config->get('config_mail_engine'));

		$mail->parameter = $this->config->get('config_mail_parameter');

		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$subject = $this->language->get('text_token_revoked_subject');
		$message = $this->language->get('text_token_revoked_message');

		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setReplyTo($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(strip_tags($message));
		$mail->setHtml($message);
		
		$mail->send();
	}

	public function cronEmail($result) {
		$br = '<br />';

		$subject = $this->language->get('text_cron_subject');

		$message = $this->language->get('text_cron_message') . $br . $br;

		$message .= '<strong>' . $this->language->get('text_cron_summary_token_heading') . '</strong>' . $br;

		if ($result['token_update_error']) {
			$message .= $result['token_update_error'] . $br . $br;
		} else {
			$message .= $this->language->get('text_cron_summary_token_updated') . $br . $br;
		}

		if (!empty($result['transaction_error'])) {
			$message .= '<strong>' . $this->language->get('text_cron_summary_error_heading') . '</strong>' . $br;

			$message .= implode($br, $result['transaction_error']) . $br . $br;
		}

		if (!empty($result['transaction_fail'])) {
			$message .= '<strong>' . $this->language->get('text_cron_summary_fail_heading') . '</strong>' . $br;

			foreach ($result['transaction_fail'] as $order_recurring_id => $amount) {
				$message .= sprintf($this->language->get('text_cron_fail_charge'), $order_recurring_id, $amount) . $br;
			}
		}

		if (!empty($result['transaction_success'])) {
			$message .= '<strong>' . $this->language->get('text_cron_summary_success_heading') . '</strong>' . $br;

			foreach ($result['transaction_success'] as $order_recurring_id => $amount) {
				$message .= sprintf($this->language->get('text_cron_success_charge'), $order_recurring_id, $amount) . $br;
			}
		}

		$mail = new Mail($this->config->get('config_mail_engine'));

		$mail->parameter = $this->config->get('config_mail_parameter');

		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($this->config->get('payment_squareup_cron_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setReplyTo($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(strip_tags($message));
		$mail->setHtml($message);
		$mail->send();

	}

	public function recurringPayments() {
		return (bool)$this->config->get('payment_squareup_recurring_status');
	}

	public function createRecurring($recurring, $order_id, $description, $reference) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring` SET `order_id` = '" . (int)$order_id . "', `date_added` = NOW(), `status` = '" . self::RECURRING_ACTIVE . "', `product_id` = '" . (int)$recurring['product_id'] . "', `product_name` = '" . $this->db->escape($recurring['name']) . "', `product_quantity` = '" . $this->db->escape($recurring['quantity']) . "', `recurring_id` = '" . (int)$recurring['recurring']['recurring_id'] . "', `recurring_name` = '" . $this->db->escape($recurring['recurring']['name']) . "', `recurring_description` = '" . $this->db->escape($description) . "', `recurring_frequency` = '" . $this->db->escape($recurring['recurring']['frequency']) . "', `recurring_cycle` = '" . (int)$recurring['recurring']['cycle'] . "', `recurring_duration` = '" . (int)$recurring['recurring']['duration'] . "', `recurring_price` = '" . (float)$recurring['recurring']['price'] . "', `trial` = '" . (int)$recurring['recurring']['trial'] . "', `trial_frequency` = '" . $this->db->escape($recurring['recurring']['trial_frequency']) . "', `trial_cycle` = '" . (int)$recurring['recurring']['trial_cycle'] . "', `trial_duration` = '" . (int)$recurring['recurring']['trial_duration'] . "', `trial_price` = '" . (float)$recurring['recurring']['trial_price'] . "', `reference` = '" . $this->db->escape($reference) . "'");

		return $this->db->getLastId();
	}

	public function validateCRON() {
		if (!$this->config->get('payment_squareup_status') || !$this->config->get('payment_squareup_recurring_status')) {
			return false;
		}

		if (isset($this->request->get['cron_token']) && $this->request->get['cron_token'] == $this->config->get('payment_squareup_cron_token')) {
			return true;
		}

		if (defined('SQUAREUP_ROUTE')) {
			return true;
		}

		return false;
	}

	public function updateToken() {
		try {
			$response = $this->squareup->refreshToken();

			if (!isset($response['access_token']) || !isset($response['token_type']) || !isset($response['expires_at']) || !isset($response['merchant_id']) || $response['merchant_id'] != $this->config->get('payment_squareup_merchant_id')) {
				return $this->language->get('error_squareup_cron_token');
			} else {
				$this->editTokenSetting(array(
					'payment_squareup_access_token' => $response['access_token'],
					'payment_squareup_access_token_expires' => $response['expires_at']
				));
			}
		} catch (\Squareup\Exception $e) {
			return $e->getMessage();
		}

		return '';
	}

	public function getBillingAddress($order_info) {
		$this->load->model('localisation/country');

		$billing_address = array();
		$billing_country_info = $this->model_localisation_country->getCountry($order_info['payment_country_id']);

		if (!empty($billing_country_info)) {
			$billing_address = array(
				'first_name' => $order_info['payment_firstname'],
				'last_name' => $order_info['payment_lastname'],
				'address_line_1' => $order_info['payment_address_1'],
				'address_line_2' => $order_info['payment_address_2'],
				'address_line_3' => '',
				'locality' => $order_info['payment_city'],
				'sublocality' => '',
				'sublocality_2' => '',
				'sublocality_3' => '',
				'administrative_district_level_1' => $order_info['payment_zone'],
				'administrative_district_level_2' => '',
				'administrative_district_level_3' => '',
				'postal_code' => $order_info['payment_postcode'],
				'country' => $billing_country_info['iso_code_2']
//				'organization' => $order_info['payment_company']
			);
			if ($billing_country_info['iso_code_2']=='GB') {
				$billing_address['administrative_district_level_1'] = '';
			}
		} else {
			$error = $this->language->get('error_missing_billing_address');
			throw new \Squareup\Exception($this->registry, $error);
		}

		return $billing_address;
	}

	public function nextRecurringPayments() {
		$payments = array();

		$this->load->library('squareup');
		$this->load->model('checkout/order');

		// get the order_recurring records with status=self::RECURRING_ACTIVE
		$sql = "SELECT * FROM `" . DB_PREFIX . "order_recurring` WHERE `status`='" . self::RECURRING_ACTIVE . "';";
		$query = $this->db->query($sql);
		$recurrings = $query->rows;

		// find the next payments
		foreach ($recurrings as $recurring) {
			if (!$this->paymentIsDue($recurring['order_recurring_id'])) {
				continue;
			}

			$order_info = $this->model_checkout_order->getOrder($recurring['order_id']);
			if (empty($order_info)) {
				continue;
			}

			$billing_address = $this->getBillingAddress($order_info);
			$price = (float)($recurring['trial'] ? $recurring['trial_price'] : $recurring['recurring_price']);
			$currency = $order_info['currency_code'];

			$payments[] = array(
				'is_free' => $price == 0,
				'order_id' => $recurring['order_id'],
				'order_recurring_id' => $recurring['order_recurring_id'],
				'billing_address' => $billing_address,
				'recurring_price' => $price,
				'recurring_currency' => $currency,
				'recurring_reference' => $recurring['reference'],
				'email' => $order_info['email'],
				'phone' => $order_info['telephone']
			);
		}

		return $payments;
	}

	public function addRecurringTransaction($order_recurring_id, $reference, $amount, $status) {
		if ($status) {
			$type = self::TRANSACTION_PAYMENT;
		} else {
			$type = self::TRANSACTION_FAILED;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET order_recurring_id='" . (int)$order_recurring_id . "', reference='" . $this->db->escape($reference) . "', type='" . (int)$type . "', amount='" . (float)$amount . "', date_added=NOW()");
	}

	public function updateRecurringExpired($order_recurring_id) {
		$recurring_info = $this->getRecurring($order_recurring_id);

		if ($recurring_info['trial']) {
			// If we are in trial, we need to check if the trial will end at some point
			$expirable = (bool)$recurring_info['trial_duration'];
		} else {
			// If we are not in trial, we need to check if the recurring will end at some point
			$expirable = (bool)$recurring_info['recurring_duration'];
		}

		// If recurring payment can expire (trial_duration > 0 AND recurring_duration > 0)
		if ($expirable) {
			$number_of_successful_payments = $this->getTotalSuccessfulPayments($order_recurring_id);

			$total_duration = (int)$recurring_info['trial_duration'] + (int)$recurring_info['recurring_duration'];
			
			// If successful payments exceed total_duration
			if ($number_of_successful_payments >= $total_duration) {
				$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET status='" . self::RECURRING_EXPIRED . "' WHERE order_recurring_id='" . (int)$order_recurring_id . "'");

				return true;
			}
		}

		return false;
	}

	public function updateRecurringTrial($order_recurring_id) {
		$recurring_info = $this->getRecurring($order_recurring_id);

		// If recurring payment is in trial and can expire (trial_duration > 0)
		if ($recurring_info['trial'] && $recurring_info['trial_duration']) {
			$number_of_successful_payments = $this->getTotalSuccessfulPayments($order_recurring_id);

			// If successful payments exceed trial_duration
			if ($number_of_successful_payments >= $recurring_info['trial_duration']) {
				$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET trial='0' WHERE order_recurring_id='" . (int)$order_recurring_id . "'");

				return true;
			}
		}

		return false;
	}

	public function suspendRecurringProfile($order_recurring_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET status='" . self::RECURRING_SUSPENDED . "' WHERE order_recurring_id='" . (int)$order_recurring_id . "'");

		return true;
	}

	private function getLastSuccessfulRecurringPaymentDate($order_recurring_id) {
		return $this->db->query("SELECT date_added FROM `" . DB_PREFIX . "order_recurring_transaction` WHERE order_recurring_id='" . (int)$order_recurring_id . "' AND type='" . self::TRANSACTION_PAYMENT . "' ORDER BY date_added DESC LIMIT 0,1")->row['date_added'];
	}

	private function getRecurring($order_recurring_id) {
		$recurring_sql = "SELECT * FROM `" . DB_PREFIX . "order_recurring` WHERE order_recurring_id='" . (int)$order_recurring_id . "'";

		return $this->db->query($recurring_sql)->row;
	}

	private function getTotalSuccessfulPayments($order_recurring_id) {
		return $this->db->query("SELECT COUNT(*) as total FROM `" . DB_PREFIX . "order_recurring_transaction` WHERE order_recurring_id='" . (int)$order_recurring_id . "' AND type='" . self::TRANSACTION_PAYMENT . "'")->row['total'];
	}

	private function paymentIsDue($order_recurring_id) {
		// We know the recurring profile is active.
		$recurring_info = $this->getRecurring($order_recurring_id);

		if ($recurring_info['trial']) {
			$frequency = $recurring_info['trial_frequency'];
			$cycle = (int)$recurring_info['trial_cycle'];
		} else {
			$frequency = $recurring_info['recurring_frequency'];
			$cycle = (int)$recurring_info['recurring_cycle'];
		}
		// Find date of last payment
		if (!$this->getTotalSuccessfulPayments($order_recurring_id)) {
			$previous_time = strtotime($recurring_info['date_added']);
		} else {
			$previous_time = strtotime($this->getLastSuccessfulRecurringPaymentDate($order_recurring_id));
		}

		switch ($frequency) {
			case 'day' : $time_interval = 24 * 3600; break;
			case 'week' : $time_interval = 7 * 24 * 3600; break;
			case 'semi_month' : $time_interval = 15 * 24 * 3600; break;
			case 'month' : $time_interval = 30 * 24 * 3600; break;
			case 'year' : $time_interval = 365 * 24 * 3600; break;
		}

		$due_date = date('Y-m-d', $previous_time + ($time_interval * $cycle));

		$this_date = date('Y-m-d');

		return $this_date >= $due_date;
	}

	private function editTokenSetting($settings) {
		foreach ($settings as $key => $value) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code`='payment_squareup' AND `key`='" . $key . "'");

			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code`='payment_squareup', `key`='" . $key . "', `value`='" . $this->db->escape($value) . "', serialized=0, store_id=0");
		}
	}

	private function mailResendPeriodExpired($key) {
		$result = (int)$this->cache->get('squareup.' . $key);

		if (!$result) {
			// No result, therefore this is the first e-mail and the re-send period should be regarded as expired.
			$this->cache->set('squareup.' . $key, time());
		} else {
			// There is an entry in the cache. We will calculate the time difference (delta)
			$delta = time() - $result;

			if ($delta >= 15 * 60) {
				// More than 15 minutes have passed, therefore the re-send period has expired.
				$this->cache->set('squareup.' . $key, time());
			} else {
				// Less than 15 minutes have passed before the last e-mail, therefore the re-send period has not expired.
				return false;
			}
		}

		// In all other cases, the re-send period has expired.
		return true;
	}

	public function getAmountAndCurrency($order_amount) {
		// get OpenCart default currency and its total amount
		$currency = $this->config->get('config_currency');
		$amount = $order_amount;

		if ($this->config->get('payment_squareup_enable_sandbox')) {
			$location_id = $this->config->get('payment_squareup_sandbox_location_id');
			$access_token = $this->config->get('payment_squareup_sandbox_token');
		} else {
			$location_id = $this->config->get('payment_squareup_location_id');
			$access_token = $this->config->get('payment_squareup_access_token');
		}

		// if there is a location and its currency is also available on this OpenCart server
		// then use that one for the quick_pay page
		$this->load->library('squareup');
		try {
			$location = $this->squareup->retrieveLocation($access_token, $location_id);
		} catch (\Squareup\Exception $e) {
			$location = null;
		}
		if (isset($location['location']['currency'])) {
			$this->load->model('localisation/currency');
			$available_currencies = $this->model_localisation_currency->getCurrencies();
			foreach ($available_currencies as $available_currency) {
				if ($available_currency['code'] == $location['location']['currency']) {
					$amount = $this->currency->convert($order_amount, $currency, $location['location']['currency']);
					$currency = $location['location']['currency'];
					break;
				}
			}
		}

		return array($amount,$currency);
	}
}
