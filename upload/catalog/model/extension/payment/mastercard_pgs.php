<?php

class ModelExtensionPaymentMastercardPGS extends Model {
	private $notification;
	private $extension_version = '2.0.0';
	private $api_version = '40';

	public function getPartnerSolutionId() {
		return 'OPENCART_' . VERSION . '_ISENSE_' . $this->extension_version;
	}

	public function getApiVersion() {
		return $this->api_version;
	}

	public function getGateway() {
		if ($this->config->get('mastercard_pgs_gateway') != 'other') {
			return 'https://' . $this->config->get('mastercard_pgs_gateway') . '-gateway.mastercard.com';
		} else {
			$url_parts = parse_url($this->config->get('mastercard_pgs_gateway_other'));

			$url = 'https://';

			if (isset($url_parts['user']) && isset($url_parts['pass'])) {
				$url .= $url_parts['user'] . ':' . $url_parts['pass'] . '@';
			}

			if (isset($url_parts['host'])) {
				$url .= $url_parts['host'];
			} else {
				$url .= $url_parts['path'];
			}

			if (isset($url_parts['port'])) {
				$url .= ':' . $url_parts['port'];
			}

			return $url;
		}
	}

	public function getMethod($address, $total) {
		$this->load->language('extension/payment/mastercard_pgs');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('mastercard_pgs_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('mastercard_pgs_total') > 0 && $this->config->get('mastercard_pgs_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('mastercard_pgs_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$title_config = $this->config->get('mastercard_pgs_display_name');

			$method_data = array(
				'code'							=> 'mastercard_pgs',
				'title'							=> !empty($title_config[$this->config->get('config_language_id')]) ? $title_config[$this->config->get('config_language_id')] : $this->language->get('text_title'),
				'terms'							=> '',
				'sort_order'					=> $this->config->get('mastercard_pgs_sort_order')
			);
		}

		return $method_data;
	}

	public function getHeaderVars() {
		$result = array();

		foreach ($this->request->server as $key => $value) {
			if (stripos($key, 'http_') === 0) {
				$result[$key] = $value;
			}
		}

		return $result;
	}

	public function getHeaderVar($key) {
		$header_vars = $this->getHeaderVars();

		return !empty($header_vars[$key]) ? $header_vars[$key] : null;
	}

	public function getNotificationLogData() {
		$this->load->language('extension/payment/mastercard_pgs');

		$header_vars = $this->getHeaderVars();
		$post_vars = $this->request->post;
		$get_vars = $this->request->get;

		$text = PHP_EOL . sprintf($this->language->get('text_log_notification_intro'), 'HEADERS') . PHP_EOL;
		$text .= var_export($header_vars, true) . PHP_EOL;
		$text .= sprintf($this->language->get('text_log_notification_intro'), 'GET') . PHP_EOL;
		$text .= var_export($get_vars, true) . PHP_EOL;
		$text .= sprintf($this->language->get('text_log_notification_intro'), 'POST') . PHP_EOL;
		$text .= var_export($post_vars, true) . PHP_EOL;
		$text .= sprintf($this->language->get('text_log_notification_intro'), 'BODY') . PHP_EOL;
		$text .= var_export(file_get_contents('php://input'), true) . PHP_EOL;
		$text .= "==================================" . PHP_EOL;

		return $text;
	}

	public function parseWebhookNotification() {
		if (!empty($this->notification)) {
			return $this->notification;
		}

		$raw_json = file_get_contents('php://input');

		if (empty($raw_json)) {
			return false;
		}

		$json = @json_decode($raw_json, true);

		if (empty($json)) {
			return false;
		}

		$this->notification = $json;

		return $json;
	}

	public function isTransactionLogged($transaction_id, $order_id) {
		$transaction_result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "mastercard_pgs_transaction` WHERE `transaction_id`='" . $this->db->escape($transaction_id) . "' AND `order_id`='" . (int)$order_id . "'");

		return $transaction_result->num_rows > 0;
	}

	public function saveTransaction($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "mastercard_pgs_transaction` SET `transaction_id`='" . $this->db->escape($data['transaction']['id']) . "', `merchant`='" . $this->db->escape($data['merchant']) . "', `order_id`='" . (int)$data['order']['id'] . "', `partnerSolutionId`='" . $this->db->escape($data['partnerSolutionId']) . "', `response_gatewayCode`='" . $this->db->escape($data['response']['gatewayCode']) . "', `result`='" . $this->db->escape($data['result']) . "', `transaction_type`='" . $this->db->escape($data['transaction']['type']) . "', `transaction_amount`='" . (float)$data['transaction']['amount'] . "', `transaction_currency`='" . $this->db->escape($data['transaction']['currency']) . "', `billing_address_city`='" . $this->db->escape($data['billing']['address']['city']) . "', `billing_address_company`='" . $this->db->escape($data['billing']['address']['company']) . "', `billing_address_country`='" . $this->db->escape($data['billing']['address']['country']) . "', `billing_address_postcodeZip`='" . $this->db->escape($data['billing']['address']['postcodeZip']) . "', `billing_address_stateProvince`='" . $this->db->escape($data['billing']['address']['stateProvince']) . "', `billing_address_street`='" . $this->db->escape($data['billing']['address']['street']) . "', `risk_response_gatewayCode`='" . $this->db->escape($data['risk']['response']['gatewayCode']) . "', `risk_response_totalScore`='" . (int)$data['risk']['response']['totalScore'] . "', `version`='" . $this->db->escape($data['version']) . "', `device_browser`='" . $this->db->escape($data['device']['browser']) . "', `device_ipAddress`='" . $this->db->escape($data['device']['ipAddress']) . "', `timeOfRecord`='" . $this->db->escape($data['timeOfRecord']) . "', `notification_date`=NOW()");

		return $this->db->getLastId();
	}

	public function updateTransaction($data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "mastercard_pgs_transaction` SET `merchant`='" . $this->db->escape($data['merchant']) . "', `partnerSolutionId`='" . $this->db->escape($data['partnerSolutionId']) . "', `response_gatewayCode`='" . $this->db->escape($data['response']['gatewayCode']) . "', `result`='" . $this->db->escape($data['result']) . "', `transaction_type`='" . $this->db->escape($data['transaction']['type']) . "', `transaction_amount`='" . (float)$data['transaction']['amount'] . "', `transaction_currency`='" . $this->db->escape($data['transaction']['currency']) . "', `billing_address_city`='" . $this->db->escape($data['billing']['address']['city']) . "', `billing_address_company`='" . $this->db->escape($data['billing']['address']['company']) . "', `billing_address_country`='" . $this->db->escape($data['billing']['address']['country']) . "', `billing_address_postcodeZip`='" . $this->db->escape($data['billing']['address']['postcodeZip']) . "', `billing_address_stateProvince`='" . $this->db->escape($data['billing']['address']['stateProvince']) . "', `billing_address_street`='" . $this->db->escape($data['billing']['address']['street']) . "', `risk_response_gatewayCode`='" . $this->db->escape($data['risk']['response']['gatewayCode']) . "', `risk_response_totalScore`='" . (int)$data['risk']['response']['totalScore'] . "', `version`='" . $this->db->escape($data['version']) . "', `device_browser`='" . $this->db->escape($data['device']['browser']) . "', `device_ipAddress`='" . $this->db->escape($data['device']['ipAddress']) . "', `timeOfRecord`='" . $this->db->escape($data['timeOfRecord']) . "', `notification_date`=NOW() WHERE `transaction_id`='" . $this->db->escape($data['transaction']['id']) . "' AND `order_id`='" . (int)$data['order']['id'] . "'");

		return $this->db->getLastId();
	}

	public function getCards($customer_id) {
		$this->load->language('extension/payment/mastercard_pgs');

		$result = array();

		foreach ($this->getTokens($customer_id) as $token_data) {
			$response = $this->api('token/' . urlencode($token_data['token']));

			if (
				empty($response['result']) || 
				$response['result'] != 'SUCCESS' ||
				$response['status'] != 'VALID' ||
				$response['sourceOfFunds']['type'] != 'CARD' ||
				empty($response['sourceOfFunds']['provided']['card']['brand']) ||
				empty($response['sourceOfFunds']['provided']['card']['number'])
			) {
				// We don't need the token if it is not in the api, or if it is invalid.
				$this->db->query("DELETE FROM `" . DB_PREFIX . "mastercard_pgs_token` WHERE mastercard_pgs_token_id='" . (int)$token_data['mastercard_pgs_token_id'] . "'");
			} else {
				$result[] = array(
					'card_number' => $response['sourceOfFunds']['provided']['card']['number'],
					'token' => base64_encode($token_data['token']),
					'text' => $this->language->get('text_card_brand_' . strtolower($response['sourceOfFunds']['provided']['card']['brand'])) . ' - ' . $response['sourceOfFunds']['provided']['card']['number']
				);
			}
		}

		$sort_order = array();

		foreach ($result as $key => $value) {
			$sort_order[$key] = $value['text'];
		}

		array_multisort($sort_order, SORT_ASC, $result);

		return $result;
	}

	public function getTokens($customer_id) {
		$tokens_result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "mastercard_pgs_token` WHERE customer_id='" . (int)$customer_id . "'");

		return $tokens_result->rows;
	}

	public function saveToken($order_id, $token, $session_id, $sourceOfFunds) {
		if (empty($sourceOfFunds['provided']['card']['number']) || empty($sourceOfFunds['type']) || $sourceOfFunds['type'] != 'CARD') {
			return;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		$customer_id = $order_info['customer_id'];

		if (empty($customer_id)) {
			return;
		}

		foreach ($this->getCards($customer_id) as $card_data) {
			if ($card_data['card_number'] == $sourceOfFunds['provided']['card']['number']) {
				return; // No need to save the token if it already exists.
			}
		}

		// At this point we know the card is not tokenized
		$api_data = array();
		$api_data['session']['id'] = $session_id;
		$api_data['sourceOfFunds']['type'] = $sourceOfFunds['type'];

		$response = $this->api('token/' . $token, $api_data, 'PUT');

		if (!empty($response['result']) && $response['result'] == 'SUCCESS' && $response['status'] == 'VALID') {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "mastercard_pgs_token` SET `customer_id`='" . (int)$customer_id . "', `token`='" . $this->db->escape($token) . "', `date_added`=NOW()");
		} else {
			// Initial attempt failed. Try the other strategy
			$response_second = $this->api('token', $api_data, 'POST');

			if (!empty($response_second['result']) && $response_second['result'] == 'SUCCESS' && $response_second['status'] == 'VALID') {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "mastercard_pgs_token` SET `customer_id`='" . (int)$customer_id . "', `token`='" . $this->db->escape($response_second['token']) . "', `date_added`=NOW()");
			}
		}
	}

	public function editOrderPaymentDetails($order_id, $data) {
		$country_result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_3`='" . $data['country'] . "'");

		$payment_country_id = !empty($country_result->row['country_id']) ? $country_result->row['country_id'] : 0;
		$payment_country = !empty($country_result->row['name']) ? $country_result->row['name'] : '';

		$zone_result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE `country_id`='" . $payment_country_id . "' AND LOWER(`name`)='" . $this->db->escape(strtolower(trim($data['stateProvince']))) . "'");

		$payment_zone_id = !empty($zone_result->row['zone_id']) ? $zone_result->row['zone_id'] : 0;
		$payment_zone = !empty($zone_result->row['name']) ? $zone_result->row['name'] : '';

		$payment_address_1 = $data['street'];
		$payment_address_2 = !empty($data['street2']) ? $data['street2'] : '';

		$payment_postcode = $data['postcodeZip'];
		$payment_city = $data['city'];
		$payment_company = !empty($data['company']) ? $data['company'] : '';

		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `payment_country_id`='" . (int)$payment_country_id . "', `payment_country`='" . $this->db->escape($payment_country) . "', `payment_zone_id`='" . (int)$payment_zone_id . "', `payment_zone`='" . $this->db->escape($payment_zone) . "', `payment_postcode`='" . $this->db->escape($payment_postcode) . "', `payment_city`='" . $this->db->escape($payment_city) . "', `payment_address_1`='" . $this->db->escape($payment_address_1) . "', `payment_address_2`='" . $this->db->escape($payment_address_2) . "', `payment_company`='" . $this->db->escape($payment_company) . "'");
	}

	public function addOrderHistory($order_history) {
		$this->load->model('checkout/order');

		$this->model_checkout_order->addOrderHistory($order_history['order_id'], $order_history['order_status_id'], $order_history['message']);
	}

	public function initCheckoutSession($order_id, $currency) {
		$this->clearCheckoutSession();

		$data = array();
		$data['apiOperation'] = 'CREATE_CHECKOUT_SESSION';
		$data['partnerSolutionId'] = $this->getPartnerSolutionId();
		$data['order']['id'] = $order_id;
		$data['order']['currency'] = $currency;
		$data['order']['notificationUrl'] = $this->url->link('extension/payment/mastercard_pgs/callback', '', true);

		$response = $this->api('session', $data, 'POST');

		if (!empty($response['result']) && $response['result'] == 'SUCCESS') {
			$this->session->data['mastercard_pgs'] = $response;

			return true;
		}

		return false;
	}

	public function clearCheckoutSession() {
		unset($this->session->data['mastercard_pgs']);
	}

	public function api($api_method, $data = array(), $method = 'GET') {
		$this->load->language('extension/payment/mastercard_pgs');

		$gateway = $this->getGateway() . '/api/rest/version/' . $this->getApiVersion() . '/merchant/';

		$url = $gateway . $this->config->get('mastercard_pgs_merchant') . '/' . $api_method;
		
		$userid = 'merchant.' . $this->config->get('mastercard_pgs_merchant');
		$password = $this->config->get('mastercard_pgs_integration_password');

		$curl_options = array(
			CURLOPT_URL => $url,
			CURLOPT_USERPWD => $userid . ':' . $password,
			CURLOPT_RETURNTRANSFER => true
		);

		$put_fd = null;

		if ($method == 'POST') {
			$curl_options[CURLOPT_POST] = true;
			$curl_options[CURLOPT_POSTFIELDS] = json_encode($data);
		} else if ($method == 'PUT') {
			$curl_options[CURLOPT_PUT] = true;

			$write_data = json_encode($data);

			$put_fd = tmpfile();
			fwrite($put_fd, $write_data);
			fseek($put_fd, 0);

			$curl_options[CURLOPT_INFILE] = $put_fd;
			$curl_options[CURLOPT_INFILESIZE] = strlen($write_data);
		}

		$curl = curl_init();
		curl_setopt_array($curl, $curl_options);
		$output = curl_exec($curl);
		curl_close($curl);

		if ($this->config->get('mastercard_pgs_debug_log')) {
			$text = PHP_EOL . sprintf($this->language->get('text_log_api_intro'), 'REQUEST') . PHP_EOL;
			$text .= var_export($curl_options, true) . PHP_EOL;
			$text .= sprintf($this->language->get('text_log_api_intro'), 'DATA') . PHP_EOL;
			$text .= json_encode($data) . PHP_EOL;
			$text .= sprintf($this->language->get('text_log_api_intro'), 'RESPONSE') . PHP_EOL;
			$text .= var_export($output, true) . PHP_EOL;
			$text .= "==================================" . PHP_EOL;

			$this->log->write($text);
		}

		if (is_resource($put_fd)) {
			fclose($put_fd);
		}

		return json_decode($output, true);
	}
}
