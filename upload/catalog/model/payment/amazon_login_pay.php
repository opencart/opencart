<?php
class ModelPaymentAmazonLoginPay extends Model {

	public function getCountry($iso2) {
		return $this->db->query("SELECT `country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format` FROM `" . DB_PREFIX . "country` WHERE `iso_code_2` = '" . $this->db->escape(strtoupper($iso2)) . "' AND `status` = 1 LIMIT 1")->row;
	}

	public function getZone($name, $country_id) {
		return $this->db->query("SELECT `zone_id`, `code` FROM `" . DB_PREFIX . "zone` WHERE (LOWER(`name`) LIKE '" . $this->db->escape(strtolower($name)) . "' OR `code` LIKE '" . $this->db->escape(strtolower($name)) . "') AND `country_id` = " . (int)$country_id . " LIMIT 1")->row;
	}

	public function addTaxesForTotals($order_id, $totals) {
		foreach ($totals as $total) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "amazon_login_pay_order_total_tax` (`order_total_id`, `code`, `tax`) SELECT `order_total_id`, `code`, " . (float)$total['lpa_tax'] . " FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = " . (int)$order_id . " AND `code` = '" . $this->db->escape($total['code']) . "' AND `title` = '" . $this->db->escape($total['title']) . "'");
		}
	}

	public function getAddress() {
		$address_paramter_data['AddressConsentToken'] = $this->session->data['access_token'];
		$address = $this->model_payment_amazon_login_pay->offAmazon('GetOrderReferenceDetails', $address_paramter_data);
		$xml = simplexml_load_string($address['ResponseBody']);
		if (isset($xml->GetOrderReferenceDetailsResult->OrderReferenceDetails->Destination->PhysicalDestination)) {
			return $xml->GetOrderReferenceDetailsResult->OrderReferenceDetails->Destination->PhysicalDestination;
		}
	}

	public function setOrderShipping($order_id, $has_free_shipping) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "amazon_login_pay_order` SET `order_id` = '" . (int)$order_id . "', `free_shipping` = '" . (int)$has_free_shipping . "',`date_added` = now(), `modified` = now() ");
		return $this->db->getLastId();
	}

	public function hasFreeShipping($order_id) {
		return $this->db->query("SELECT `free_shipping` FROM `" . DB_PREFIX . "amazon_login_pay_order` WHERE `order_id` = " . (int)$order_id)->row['free_shipping'] == '1';
	}

	public function getShippingPrice($order_id) {
		$query = $this->db->query("SELECT `value` + IF(`tax` IS NULL, 0, `tax`) AS 'price' FROM `" . DB_PREFIX . "order_total` `ot` LEFT JOIN `" . DB_PREFIX . "amazon_login_pay_order_total_tax` `ott` USING(`order_total_id`) WHERE `ot`.`code` = 'shipping' AND `order_id` = " . (int)$order_id);
		if ($query->num_rows) {
			return $query->row['price'];
		}
	}

	public function getAdditionalCharges($order_id) {
		return $this->db->query("SELECT `ot`.`title`, `ot`.`order_total_id`, `value` + IF(`tax` IS NULL, 0, `tax`) AS 'price' FROM `" . DB_PREFIX . "order_total` `ot` LEFT JOIN `" . DB_PREFIX . "amazon_login_pay_order_total_tax` `ott` USING(`order_total_id`)  WHERE `ott`.`code` NOT IN ('shipping', 'total', 'sub_total', 'tax') AND `value` > 0 AND `order_id` = " . (int)$order_id)->rows;
	}

	public function addAmazonOrderId($order_id, $amazon_authorization_id, $capture_status, $total, $currency_code) {
		$this->db->query("UPDATE `" . DB_PREFIX . "amazon_login_pay_order` SET `amazon_order_reference_id` = '" . $this->db->escape($this->session->data['lpa']['AmazonOrderReferenceId']) . "', `amazon_authorization_id` = '" . $this->db->escape($amazon_authorization_id) . "', `modified` = now(), `capture_status` = '" . $this->db->escape($capture_status) . "', `currency_code` = '" . $this->db->escape($currency_code) . "', `total` = '" . $total . "' WHERE `order_id` = '" . (int)$order_id . "'");
	}

	public function addTransaction($amazon_login_pay_order_id, $amazon_authorization_id, $amazon_capture_id, $type, $status, $total) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "amazon_login_pay_order_transaction` SET `amazon_login_pay_order_id` = '" . (int)$amazon_login_pay_order_id . "', `amazon_authorization_id` = '" . $this->db->escape($amazon_authorization_id) . "', `amazon_capture_id` = '" . $this->db->escape($amazon_capture_id) . "', `date_added` = now(), `type` = '" . $this->db->escape($type) . "', `status` = '" . $this->db->escape($status) . "', `amount` = '" . $total . "'");
	}

	public function closeOrderRef($amazon_order_reference_id) {
		$close_paramter_data = array();
		$close_paramter_data['AmazonOrderReferenceId'] = $amazon_order_reference_id;
		$close_details = $this->offAmazon('CloseOrderReference', $close_paramter_data);
		$this->validateResponse('CloseOrderReference', $close_details);
	}

	public function sendOrder($order_id, $total, $currency_code) {

		$update_paramter_data = array();
		$update_paramter_data['OrderReferenceAttributes.OrderTotal.Amount'] = $total;
		$update_paramter_data['OrderReferenceAttributes.OrderTotal.CurrencyCode'] = $currency_code;
		$update_paramter_data['OrderReferenceAttributes.SellerOrderAttributes.SellerOrderId'] = $order_id;
		$update_paramter_data['OrderReferenceAttributes.SellerOrderAttributes.StoreName'] = $this->config->get('config_name');
		if ($this->config->get('amazon_login_pay_marketplace') == 'us') {
			$update_paramter_data['OrderReferenceAttributes.PlatformId'] = 'A3GK1RS09H3A7D';
		} else {
			$update_paramter_data['OrderReferenceAttributes.PlatformId'] = 'A3EIRX2USI2KJV';
		}

		$address_paramter_data['AddressConsentToken'] = $this->session->data['access_token'];
		$address = $this->offAmazon('GetOrderReferenceDetails', $address_paramter_data);
		$address_details_response = simplexml_load_string($address['ResponseBody']);

		$reason_code = (string)$address_details_response->GetOrderReferenceDetailsResult->OrderReferenceDetails->OrderReferenceStatus->ReasonCode;

		if ($reason_code != 'InvalidPaymentMethod') {
			$update_details = $this->offAmazon('SetOrderReferenceDetails', $update_paramter_data);
			$update_details_response = $this->validateResponse('Update', $update_details);
			if ($update_details_response['redirect']) {
				return $update_details_response;
			}
		}

		$confirm_details = $this->offAmazon('ConfirmOrderReference');
		$confirm_details_response = $this->validateResponse('Confirm', $confirm_details);
		if ($confirm_details_response['redirect']) {
			return $confirm_details_response;
		}

		$response['capture_status'] = 0;
		$authorize_paramter_data = array();
		if ($this->config->get('amazon_login_pay_mode') == 'payment') {
			$authorize_paramter_data['CaptureNow'] = true;
			$authorize_paramter_data['CaptureReferenceId'] = 'capture_' . mt_rand();
			$response['capture_status'] = 1;
		}

		if ($this->config->get('amazon_login_pay_declined_code')) {
			$authorize_paramter_data['SellerAuthorizationNote'] = '{"SandboxSimulation": {"State":"Declined", "ReasonCode":"' . $this->config->get('amazon_login_pay_declined_code') . '"}}';
		}

		$authorize_paramter_data['AuthorizationAmount.Amount'] = $total;
		$authorize_paramter_data['AuthorizationAmount.CurrencyCode'] = $currency_code;
		$authorize_paramter_data['AuthorizationReferenceId'] = 'auth_' . mt_rand();
		$authorize_paramter_data['TransactionTimeout'] = 0;
		$authorize_details = $this->offAmazon('Authorize', $authorize_paramter_data);
		$authorize_details_response = $this->validateResponse('Authorize', $authorize_details);
		if (isset($authorize_details_response['redirect'])) {
			return $authorize_details_response;
		}

		$response['amazon_authorization_id'] = (string)$authorize_details_response->AuthorizeResult->AuthorizationDetails->AmazonAuthorizationId;
		$response['status'] = (string)$authorize_details_response->AuthorizeResult->AuthorizationDetails->AuthorizationStatus->State;
		if (isset($authorize_details_response->AuthorizeResult->AuthorizationDetails->IdList->member)) {
			$response['amazon_capture_id'] = (string)$authorize_details_response->AuthorizeResult->AuthorizationDetails->IdList->member;
		}

		if (isset($authorize_details_response->AuthorizeResult->AuthorizationDetails->AuthorizationBillingAddress)) {
			$response['billing_address'] = $authorize_details_response->AuthorizeResult->AuthorizationDetails->AuthorizationBillingAddress;
		}

		return $response;
	}

	public function editOrder($order_id, $order) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET  payment_firstname = '" . $this->db->escape($order['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($order['payment_lastname']) . "', payment_address_1 = '" . $this->db->escape($order['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($order['payment_address_2']) . "', payment_city = '" . $this->db->escape($order['payment_city']) . "', payment_zone = '" . $this->db->escape($order['payment_zone']) . "', payment_zone_id = " . (int)$order['payment_zone_id'] . ", payment_country = '" . $this->db->escape($order['payment_country']) . "', payment_country_id = " . (int)$order['payment_country_id'] . ", payment_postcode = '" . $this->db->escape($order['payment_postcode']) . "' WHERE order_id = " . (int)$order_id);
	}

	public function updateStatus($amazon_id, $type, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "amazon_login_pay_order_transaction` SET `status` = '" . $this->db->escape($status) . "' WHERE `amazon_" . $type . "_id` = '" . $this->db->escape($amazon_id) . "' AND `type` = '" . $this->db->escape($type) . "'");
	}

	public function authorizationIpn($xml) {
		$status = (string)$xml->AuthorizationDetails->AuthorizationStatus->State;
		$amazon_authorization_id = (string)$xml->AuthorizationDetails->AmazonAuthorizationId;
		$this->updateStatus($amazon_authorization_id, 'authorization', $status);
		if ($status == 'Declined' || $status == 'Closed') {
			$this->logger($status . ': ' . (string)$xml->AuthorizationDetails->AuthorizationStatus->ReasonCode);
		}
	}

	public function captureIpn($xml) {
		$status = (string)$xml->CaptureDetails->CaptureStatus->State;
		$amazon_capture_id = (string)$xml->CaptureDetails->AmazonCaptureId;
		$this->updateStatus($amazon_capture_id, 'capture', $status);
		if ($status == 'Declined' || $status == 'Canceled' || $status == 'Closed') {
			$this->logger($status . ': ' . (string)$xml->CaptureDetails->CaptureStatus->ReasonCode);
		}
	}

	public function refundIpn($xml) {
		$status = (string)$xml->RefundDetails->RefundStatus->State;
		$amazon_refund_id = (string)$xml->RefundDetails->AmazonRefundId;
		$this->updateStatus($amazon_refund_id, 'refund', $status);
		if ($status == 'Declined') {
			$this->logger($status . ': ' . (string)$xml->RefundDetails->RefundStatus->ReasonCode);
		}
	}

	public function capture($amazon_login_pay_order) {
		$this->logger($amazon_login_pay_order);
		$this->logger(count($amazon_login_pay_order['transactions']));
		if (count($amazon_login_pay_order['transactions']) == 1) {
			$capture_paramter_data = array();
			$capture_paramter_data['AmazonOrderReferenceId'] = $amazon_login_pay_order['amazon_order_reference_id'];
			$capture_paramter_data['AmazonAuthorizationId'] = $amazon_login_pay_order['amazon_authorization_id'];
			$capture_paramter_data['CaptureAmount.Amount'] = $amazon_login_pay_order['total'];
			$capture_paramter_data['CaptureAmount.CurrencyCode'] = $amazon_login_pay_order['currency_code'];
			$capture_paramter_data['CaptureReferenceId'] = 'capture_' . mt_rand();
			$capture_paramter_data['TransactionTimeout'] = 0;

			$capture_details = $this->offAmazon('Capture', $capture_paramter_data);
			$capture_response = $this->validateResponse('Capture', $capture_details);
			if (isset($capture_response->CaptureResult)) {
				$response['status'] = (string)$capture_response->CaptureResult->CaptureDetails->CaptureStatus->State;
				$response['amazon_capture_id'] = (string)$capture_response->CaptureResult->CaptureDetails->AmazonCaptureId;
				return $response;
			}

			return $capture_response;
		} else {
			return false;
		}
	}

	public function updateCaptureStatus($amazon_login_pay_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "amazon_login_pay_order` SET `capture_status` = '" . (int)$status . "' WHERE `amazon_login_pay_order_id` = '" . (int)$amazon_login_pay_order_id . "'");
	}

	public function getOrder($order_id) {

		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "amazon_login_pay_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($qry->num_rows) {
			$order = $qry->row;
			$order['transactions'] = $this->getTransactions($order['amazon_login_pay_order_id'], $qry->row['currency_code']);
			return $order;
		} else {
			return false;
		}
	}

	private function getTransactions($amazon_login_pay_order_id, $currency_code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "amazon_login_pay_order_transaction` WHERE `amazon_login_pay_order_id` = '" . (int)$amazon_login_pay_order_id . "'");

		$transactions = array();
		if ($query->num_rows) {
			foreach ($query->rows as $row) {
				$row['amount'] = $this->currency->format($row['amount'], $currency_code, true, true);
				$transactions[] = $row;
			}
			return $transactions;
		} else {
			return false;
		}
	}

	public function getUserInfo($access_token) {
		if ($this->config->get('amazon_login_pay_test') == 'sandbox') {
			if ($this->config->get('amazon_login_pay_marketplace') == 'uk') {
				$curl_token = curl_init('https://api.sandbox.amazon.co.uk/auth/o2/tokeninfo?access_token=' . urlencode($access_token));
				$curl_profile = curl_init('https://api.sandbox.amazon.co.uk/user/profile');
			} elseif ($this->config->get('amazon_login_pay_marketplace') == 'de') {
				$curl_token = curl_init('https://api.sandbox.amazon.de/auth/o2/tokeninfo?access_token=' . urlencode($access_token));
				$curl_profile = curl_init('https://api.sandbox.amazon.de/user/profile');
			} else {
				$curl_token = curl_init('https://api.sandbox.amazon.com/auth/o2/tokeninfo?access_token=' . urlencode($access_token));
				$curl_profile = curl_init('https://api.sandbox.amazon.com/user/profile');
			}
		} else {
			if ($this->config->get('amazon_login_pay_marketplace') == 'uk') {
				$curl_token = curl_init('https://api.amazon.co.uk/auth/o2/tokeninfo?access_token=' . urlencode($access_token));
				$curl_profile = curl_init('https://api.amazon.co.uk/user/profile');
			} elseif ($this->config->get('amazon_login_pay_marketplace') == 'de') {
				$curl_token = curl_init('https://api.amazon.de/auth/o2/tokeninfo?access_token=' . urlencode($access_token));
				$curl_profile = curl_init('https://api.amazon.de/user/profile');
			} else {
				$curl_token = curl_init('https://api.amazon.com/auth/o2/tokeninfo?access_token=' . urlencode($access_token));
				$curl_profile = curl_init('https://api.amazon.com/user/profile');
			}
		}

		curl_setopt($curl_token, CURLOPT_RETURNTRANSFER, true);

		$response_token = curl_exec($curl_token);
		curl_close($curl_token);
		$decoded_token = json_decode($response_token);

		if (!isset($decoded_token->aud) || $decoded_token->aud != $this->config->get('amazon_login_pay_client_id')) {
			$this->logger($decoded_token);
			$this->logger('the access token does not belong to us');
			return;
		}

		curl_setopt($curl_profile, CURLOPT_HTTPHEADER, array('Authorization: bearer ' . $access_token));
		curl_setopt($curl_profile, CURLOPT_RETURNTRANSFER, true);

		$response_profile = curl_exec($curl_profile);
		curl_close($curl_profile);
		$decoded_profile = json_decode($response_profile);

		return $decoded_profile;
	}

	private function validateResponse($action, $details) {
		$details_xml = simplexml_load_string($details['ResponseBody']);
		$this->logger($action);
		$this->logger($details_xml);

		$details_xml->registerXPathNamespace('m', 'http://mws.amazonservices.com/schema/OffAmazonPayments/2013-01-01');
		$error_set = $details_xml->xpath('//m:ReasonCode');

		if (isset($details_xml->Error)) {
			$this->logger($action . ' : ' . (string)$details_xml->Error->Message);
			$response['redirect'] = 'failure';
			return $response;
		} elseif (!empty($error_set)) {
			$reason_code = (string)$error_set[0];
			$response['redirect'] = 'failure';
			if ($reason_code == 'InvalidPaymentMethod') {
				$response['redirect'] = 'paymentMethod';
			} elseif ($reason_code == 'MaxCapturesProcessed') {
				return $details_xml;
			}
			$this->logger($action . ' : ' . $reason_code);
			return $response;
		}
		return $details_xml;
	}

	public function offAmazon($Action, $parameter_data = array()) {
		if ($this->config->get('amazon_login_pay_test') == 'sandbox') {
			if ($this->config->get('amazon_login_pay_marketplace') == 'us') {
				$url = 'https://mws.amazonservices.com/OffAmazonPayments_Sandbox/2013-01-01/';
			} else {
				$url = 'https://mws-eu.amazonservices.com/OffAmazonPayments_Sandbox/2013-01-01/';
			}
		} else {
			if ($this->config->get('amazon_login_pay_marketplace') == 'us') {
				$url = 'https://mws.amazonservices.com/OffAmazonPayments/2013-01-01/';
			} else {
				$url = 'https://mws-eu.amazonservices.com/OffAmazonPayments/2013-01-01/';
			}
		}

		$parameters = array();
		$parameters['AWSAccessKeyId'] = $this->config->get('amazon_login_pay_access_key');
		$parameters['Action'] = $Action;
		if (isset($parameter_data['AmazonOrderReferenceId'])) {
			$parameters['AmazonOrderReferenceId'] = $parameter_data['AmazonOrderReferenceId'];
		} else {
			$parameters['AmazonOrderReferenceId'] = $this->session->data['lpa']['AmazonOrderReferenceId'];
		}
		$parameters['SellerId'] = $this->config->get('amazon_login_pay_merchant_id');
		$parameters['SignatureMethod'] = 'HmacSHA256';
		$parameters['SignatureVersion'] = 2;
		$parameters['Timestamp'] = date('c', time());
		$parameters['Version'] = '2013-01-01';
		foreach ($parameter_data as $k => $v) {
			$parameters[$k] = $v;
		}

		$query = $this->calculateStringToSignV2($parameters, $url);

		$parameters['Signature'] = base64_encode(hash_hmac('sha256', $query, $this->config->get('amazon_login_pay_access_secret'), true));

		return $this->sendCurl($url, $parameters);
	}

	public function sendCurl($url, $parameters) {
		$query = $this->getParametersAsString($parameters);

		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($curl);
		curl_close($curl);

		list($other, $responseBody) = explode("\r\n\r\n", $response, 2);
		$other = preg_split("/\r\n|\n|\r/", $other);

		list($protocol, $code, $text) = explode(' ', trim(array_shift($other)), 3);

		return array('Status' => (int)$code, 'ResponseBody' => $responseBody);
	}

	private function getParametersAsString(array $parameters) {
		$queryParameters = array();
		foreach ($parameters as $key => $value) {
			$queryParameters[] = $key . '=' . $this->urlencode($value);
		}
		return implode('&', $queryParameters);
	}

	private function calculateStringToSignV2(array $parameters, $url) {
		$data = 'POST';
		$data .= "\n";
		$endpoint = parse_url($url);
		$data .= $endpoint['host'];
		$data .= "\n";
		$uri = array_key_exists('path', $endpoint) ? $endpoint['path'] : null;
		if (!isset($uri)) {
			$uri = "/";
		}
		$uriencoded = implode("/", array_map(array($this, "urlencode"), explode("/", $uri)));
		$data .= $uriencoded;
		$data .= "\n";
		uksort($parameters, 'strcmp');
		$data .= $this->getParametersAsString($parameters);
		return $data;
	}

	private function urlencode($value) {
		return str_replace('%7E', '~', rawurlencode($value));
	}

	public function parseRawMessage($body) {
		$snsMessage = $this->buildMessage($body);
		$ipnMessage = $this->getField("Message", $snsMessage);

		return $this->parseIpnMessage($ipnMessage);
	}

	private function buildMessage($json) {
		$message = json_decode($json, true);
		$json_error = json_last_error();

		if ($json_error != 0) {
			$errorMsg = "Error with message - content is not in json format" .
					$json_error . " " .
					$json;
			$this->logger($errorMsg);
		}
		return $message;
	}

	private function parseIpnMessage($ipnMsg) {
		$xmlDocumentElement = $this->getXmlFromIpnMessage($ipnMsg);
		return $xmlDocumentElement;
	}

	private function getXmlFromIpnMessage($ipnMsg) {
		$notificationData = $this->getField("NotificationData", $this->buildMessage($ipnMsg));
		libxml_use_internal_errors(true);
		try {
			$xml = simplexml_load_string($notificationData);
		} catch (Exception $ex) {
			$this->logger($notificationData);
		}
		return $xml;
	}

	private function getField($fieldName, $snsMessage) {
		if (array_key_exists($fieldName, $snsMessage)) {
			return $snsMessage[$fieldName];
		} else {
			return null;
		}
	}

	public function getWidgetJs() {
		if ($this->config->get('amazon_login_pay_test') == 'sandbox') {
			if ($this->config->get('amazon_login_pay_marketplace') == 'uk') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/OffAmazonPayments/uk/sandbox/lpa/js/Widgets.js';
			} elseif ($this->config->get('amazon_login_pay_marketplace') == 'de') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/OffAmazonPayments/de/sandbox/lpa/js/Widgets.js';
			} else {
				$amazon_payment_js = 'https://static-na.payments-amazon.com/OffAmazonPayments/us/sandbox/js/Widgets.js';
			}
		} else {
			if ($this->config->get('amazon_login_pay_marketplace') == 'uk') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/OffAmazonPayments/uk/lpa/js/Widgets.js';
			} elseif ($this->config->get('amazon_login_pay_marketplace') == 'de') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/OffAmazonPayments/de/lpa/js/Widgets.js';
			} else {
				$amazon_payment_js = 'https://static-na.payments-amazon.com/OffAmazonPayments/us/js/Widgets.js';
			}
		}
		return $amazon_payment_js . '?sellerId=' . $this->config->get('amazon_login_pay_merchant_id');
	}

	public function getMethod($address, $total) {
		// Not shown in the payment method list
		return array();
	}

	public function logger($data) {
		if ($this->config->get('amazon_login_pay_debug')) {
			$log = new Log('amazon_login_pay.log');
			$backtrace = debug_backtrace();
			$log->write($backtrace[1]['class'] . '::' . $backtrace[1]['function'] . ' Data:  ' . print_r($data, 1));
		}
	}

}
