<?php
class ModelPaymentAmazonLoginPay extends Model {

	public function install() {
		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "amazon_login_pay_order` (
				`amazon_login_pay_order_id` INT(11) NOT NULL AUTO_INCREMENT,
				`order_id` int(11) NOT NULL,
				`amazon_order_reference_id` varchar(255) NOT NULL,
				`amazon_authorization_id` varchar(255) NOT NULL,
				`free_shipping`  tinyint NOT NULL DEFAULT 0,
				`date_added` DATETIME NOT NULL,
				`modified` DATETIME NOT NULL,
				`capture_status` INT(1) DEFAULT NULL,
				`cancel_status` INT(1) DEFAULT NULL,
				`refund_status` INT(1) DEFAULT NULL,
				`currency_code` CHAR(3) NOT NULL,
				`total` DECIMAL( 10, 2 ) NOT NULL,
				KEY `amazon_order_reference_id` (`amazon_order_reference_id`),
				PRIMARY KEY `amazon_login_pay_order_id` (`amazon_login_pay_order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "amazon_login_pay_order_total_tax` (
				`order_total_id`  INT,
				`code` VARCHAR(255),
				`tax` DECIMAL(10, 4) NOT NULL,
				PRIMARY KEY (`order_total_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "amazon_login_pay_order_transaction` (
			  `amazon_login_pay_order_transaction_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `amazon_login_pay_order_id` INT(11) NOT NULL,
			  `amazon_authorization_id` varchar(255),
			  `amazon_capture_id` varchar(255),
			  `amazon_refund_id` varchar(255),
			  `date_added` DATETIME NOT NULL,
			  `type` ENUM('authorization', 'capture', 'refund', 'cancel') DEFAULT NULL,
			  `status` ENUM('Open', 'Pending', 'Completed', 'Suspended', 'Declined', 'Closed', 'Canceled') DEFAULT NULL,
			  `amount` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`amazon_login_pay_order_transaction_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;
			");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazon_login_pay_order`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazon_login_pay_order_total_tax`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "amazon_login_pay_order_transaction`;");
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

	public function cancel($amazon_login_pay_order) {
		$total_captured = $this->getTotalCaptured($amazon_login_pay_order['amazon_login_pay_order_id']);

		if (!empty($amazon_login_pay_order) && $total_captured == 0) {

			$cancel_response = array();
			$cancel_paramter_data = array();

			$cancel_paramter_data['AmazonOrderReferenceId'] = $amazon_login_pay_order['amazon_order_reference_id'];
			$cancel_details = $this->offAmazon('CancelOrderReference', $cancel_paramter_data);
			$cancel_details_xml = simplexml_load_string($cancel_details['ResponseBody']);
			$this->logger($cancel_details_xml);
			if (isset($cancel_details_xml->Error)) {
				$cancel_response['status'] = 'Error';
				$cancel_response['status_detail'] = (string)$cancel_details_xml->Error->Code . ': ' . (string)$cancel_details_xml->Error->Message;
			} else {
				$cancel_response['status'] = 'Completed';
			}
			return $cancel_response;
		} else {
			return false;
		}
	}

	public function updateCancelStatus($amazon_login_pay_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "amazon_login_pay_order` SET `cancel_status` = '" . (int)$status . "' WHERE `amazon_login_pay_order_id` = '" . (int)$amazon_login_pay_order_id . "'");
	}

	public function capture($amazon_login_pay_order, $amount) {
		$total_captured = $this->getTotalCaptured($amazon_login_pay_order['amazon_login_pay_order_id']);

		if (!empty($amazon_login_pay_order) && $amazon_login_pay_order['capture_status'] == 0 && ($total_captured + $amount <= $amazon_login_pay_order['total'])) {
			if (count($amazon_login_pay_order['transactions']) != 1) {
				$amazon_authorization = $this->authorize($amazon_login_pay_order, $amount);
				if (isset($amazon_authorization['AmazonAuthorizationId'])) {
					$amazon_authorization_id = $amazon_authorization['AmazonAuthorizationId'];
				} else {
					return $amazon_authorization;
				}
			} else {
				$amazon_authorization_id = $amazon_login_pay_order['amazon_authorization_id'];
			}

			$capture_paramter_data = array();
			$capture_paramter_data['AmazonOrderReferenceId'] = $amazon_login_pay_order['amazon_order_reference_id'];
			$capture_paramter_data['AmazonAuthorizationId'] = $amazon_authorization_id;
			$capture_paramter_data['CaptureAmount.Amount'] = $amount;
			$capture_paramter_data['CaptureAmount.CurrencyCode'] = $amazon_login_pay_order['currency_code'];
			$capture_paramter_data['CaptureReferenceId'] = 'capture_' . mt_rand();
			$capture_paramter_data['TransactionTimeout'] = 0;
			$capture_details = $this->offAmazon('Capture', $capture_paramter_data);

			$capture_response = $this->validateResponse('Capture', $capture_details);
			$capture_response['AmazonAuthorizationId'] = $amazon_authorization_id;
			return $capture_response;
		} else {
			return false;
		}
	}

	private function authorize($amazon_login_pay_order, $amount) {
		$authorize_paramter_data = array();
		$authorize_paramter_data['AmazonOrderReferenceId'] = $amazon_login_pay_order['amazon_order_reference_id'];
		$authorize_paramter_data['AuthorizationAmount.Amount'] = $amount;
		$authorize_paramter_data['AuthorizationAmount.CurrencyCode'] = $amazon_login_pay_order['currency_code'];
		$authorize_paramter_data['AuthorizationReferenceId'] = 'auth_' . mt_rand();
		$authorize_paramter_data['TransactionTimeout'] = 0;
		$authorize_details = $this->offAmazon('Authorize', $authorize_paramter_data);

		return $this->validateResponse('Authorize', $authorize_details);
	}

	public function closeOrderRef($amazon_order_reference_id) {
		$close_paramter_data = array();
		$close_paramter_data['AmazonOrderReferenceId'] = $amazon_order_reference_id;
		$this->offAmazon('CloseOrderReference', $close_paramter_data);
		$close_details = $this->offAmazon('CloseOrderReference', $close_paramter_data);
		$this->logger($close_details);
	}

	public function updateCaptureStatus($amazon_login_pay_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "amazon_login_pay_order` SET `capture_status` = '" . (int)$status . "' WHERE `amazon_login_pay_order_id` = '" . (int)$amazon_login_pay_order_id . "'");
	}

	public function refund($amazon_login_pay_order, $amount) {
		if (!empty($amazon_login_pay_order) && $amazon_login_pay_order['refund_status'] != 1) {
			$amazon_captures_remaining = $this->getUnCaptured($amazon_login_pay_order['amazon_login_pay_order_id']);

			$refund_response = array();
			$i = 0;
			$count = count($amazon_captures_remaining);
			for ($amount; $amount > 0 && $count > $i; $amount -= $amazon_captures_remaining[$i++]['capture_remaining']) {
				$refund_amount = $amount;
				if ($amazon_captures_remaining[$i]['capture_remaining'] <= $amount) {
					$refund_amount = $amazon_captures_remaining[$i]['capture_remaining'];
				}

				$refund_paramter_data = array();
				$refund_paramter_data['AmazonOrderReferenceId'] = $amazon_login_pay_order['amazon_order_reference_id'];
				$refund_paramter_data['AmazonCaptureId'] = $amazon_captures_remaining[$i]['amazon_capture_id'];
				$refund_paramter_data['RefundAmount.Amount'] = $refund_amount;
				$refund_paramter_data['RefundAmount.CurrencyCode'] = $amazon_login_pay_order['currency_code'];
				$refund_paramter_data['RefundReferenceId'] = 'refund_' . mt_rand();
				$refund_paramter_data['TransactionTimeout'] = 0;
				$refund_details = $this->offAmazon('Refund', $refund_paramter_data);
				$refund_response[$i] = $this->validateResponse('Refund', $refund_details);
				$refund_response[$i]['amazon_authorization_id'] = $amazon_captures_remaining[$i]['amazon_authorization_id'];
				$refund_response[$i]['amazon_capture_id'] = $amazon_captures_remaining[$i]['amazon_capture_id'];
				$refund_response[$i]['amount'] = $refund_amount;
			}

			return $refund_response;
		} else {
			return false;
		}
	}

	public function getUnCaptured($amazon_login_pay_order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "amazon_login_pay_order_transaction` WHERE (`type` = 'refund' OR `type` = 'capture') AND `amazon_login_pay_order_id` = '" . (int)$amazon_login_pay_order_id . "' ORDER BY `date_added`");
		$uncaptured = array();
		foreach ($qry->rows as $row) {
			$uncaptured[$row['amazon_capture_id']]['amazon_authorization_id'] = $row['amazon_authorization_id'];
			$uncaptured[$row['amazon_capture_id']]['amazon_capture_id'] = $row['amazon_capture_id'];
			if (isset($uncaptured[$row['amazon_capture_id']]['capture_remaining'])) {
				$uncaptured[$row['amazon_capture_id']]['capture_remaining'] += $row['amount'];
			} else {
				$uncaptured[$row['amazon_capture_id']]['capture_remaining'] = $row['amount'];
			}

			if ($uncaptured[$row['amazon_capture_id']]['capture_remaining'] == 0) {
				unset($uncaptured[$row['amazon_capture_id']]);
			}
		}
		return array_values($uncaptured);
	}

	public function updateRefundStatus($amazon_login_pay_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "amazon_login_pay_order` SET `refund_status` = '" . (int)$status . "' WHERE `amazon_login_pay_order_id` = '" . (int)$amazon_login_pay_order_id . "'");
	}

	public function getCapturesRemaining($amazon_login_pay_order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "amazon_login_pay_order_transaction` WHERE `amazon_login_pay_order_id` = '" . (int)$amazon_login_pay_order_id . "' AND capture_remaining != '0' ORDER BY `date_added`");
		if ($query->num_rows) {
			return $query->rows;
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

	public function addTransaction($amazon_login_pay_order_id, $type, $status, $total, $amazon_authorization_id = null, $amazon_capture_id = null, $amazon_refund_id = null) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "amazon_login_pay_order_transaction` SET `amazon_login_pay_order_id` = '" . (int)$amazon_login_pay_order_id . "',`amazon_authorization_id` = '" . $this->db->escape($amazon_authorization_id) . "',`amazon_capture_id` = '" . $this->db->escape($amazon_capture_id) . "',`amazon_refund_id` = '" . $this->db->escape($amazon_refund_id) . "',  `date_added` = now(), `type` = '" . $this->db->escape($type) . "', `amount` = '" . (double)$total . "', `status` = '" . $this->db->escape($status) . "'");
	}

	public function getTotalCaptured($amazon_login_pay_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "amazon_login_pay_order_transaction` WHERE `amazon_login_pay_order_id` = '" . (int)$amazon_login_pay_order_id . "' AND (`type` = 'capture' OR `type` = 'refund') AND (`status` = 'Completed' OR `status` = 'Closed')");

		return (double)$query->row['total'];
	}

	public function getTotalRefunded($amazon_login_pay_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "amazon_login_pay_order_transaction` WHERE `amazon_login_pay_order_id` = '" . (int)$amazon_login_pay_order_id . "' AND 'refund'");

		return (double)$query->row['total'];
	}

	public function validateDetails($data) {
		$validate_paramter_data = array();
		$validate_paramter_data['AWSAccessKeyId'] = $data['amazon_login_pay_access_key'];
		$validate_paramter_data['SellerId'] = $data['amazon_login_pay_merchant_id'];
		$validate_paramter_data['AmazonOrderReferenceId'] = 'validate details';
		$validate_details = $this->offAmazon('GetOrderReferenceDetails', $validate_paramter_data);
		$validate_response = $this->validateResponse('GetOrderReferenceDetails', $validate_details);
		if($validate_response['error_code'] && $validate_response['error_code'] != 'InvalidOrderReferenceId'){
			return $validate_response;
		}
	}

	public function offAmazon($Action, $parameter_data, $post_data = array()) {
		if(!empty($post_data)){
			$merchant_id = $post_data['amazon_login_pay_merchant_id'];
			$access_key = $post_data['amazon_login_pay_access_key'];
			$access_secret = $post_data['amazon_login_pay_access_secret'];
			$test = $post_data['amazon_login_pay_test'];
			$marketplace = $post_data['amazon_login_pay_marketplace'];
		} else {
			$merchant_id = $this->config->get('amazon_login_pay_merchant_id');
			$access_key = $this->config->get('amazon_login_pay_access_key');
			$access_secret = $this->config->get('amazon_login_pay_access_secret');
			$test = $this->config->get('amazon_login_pay_test');
			$marketplace = $this->config->get('amazon_login_pay_marketplace');

		}

		if ($test == 'sandbox') {
			if ($marketplace == 'us') {
				$url = 'https://mws.amazonservices.com/OffAmazonPayments_Sandbox/2013-01-01/';
			} else {
				$url = 'https://mws-eu.amazonservices.com/OffAmazonPayments_Sandbox/2013-01-01/';
			}
		} else {
			if ($marketplace == 'us') {
				$url = 'https://mws.amazonservices.com/OffAmazonPayments/2013-01-01/';
			} else {
				$url = 'https://mws-eu.amazonservices.com/OffAmazonPayments/2013-01-01/';
			}
		}

		$parameters = array();
		$parameters['AWSAccessKeyId'] = $access_key;
		$parameters['Action'] = $Action;
		$parameters['SellerId'] = $merchant_id;
		$parameters['SignatureMethod'] = 'HmacSHA256';
		$parameters['SignatureVersion'] = 2;
		$parameters['Timestamp'] = date('c', time());
		$parameters['Version'] = '2013-01-01';
		foreach ($parameter_data as $k => $v) {
			$parameters[$k] = $v;
		}

		$query = $this->calculateStringToSignV2($parameters, $url);

		$parameters['Signature'] = base64_encode(hash_hmac('sha256', $query, $access_secret, true));

		return $this->sendCurl($url, $parameters);
	}

	private function validateResponse($action, $details) {
		$details_xml = simplexml_load_string($details['ResponseBody']);
		$this->logger($details_xml);
		switch ($action) {
			case 'Authorize':
				$result = 'AuthorizeResult';
				$details = 'AuthorizationDetails';
				$status = 'AuthorizationStatus';
				$amazon_id = 'AmazonAuthorizationId';
				break;
			case 'Capture':
				$result = 'CaptureResult';
				$details = 'CaptureDetails';
				$status = 'CaptureStatus';
				$amazon_id = 'AmazonCaptureId';
				break;
			case 'Refund':
				$result = 'RefundResult';
				$details = 'RefundDetails';
				$status = 'RefundStatus';
				$amazon_id = 'AmazonRefundId';
		}

		$details_xml->registerXPathNamespace('m', 'http://mws.amazonservices.com/schema/OffAmazonPayments/2013-01-01');
		$error_set = $details_xml->xpath('//m:ReasonCode');

		if (isset($details_xml->Error)) {
			$response['status'] = 'Error';
			$response['error_code'] = (string)$details_xml->Error->Code;
			$response['status_detail'] = (string)$details_xml->Error->Code . ': ' . (string)$details_xml->Error->Message;
		} elseif (!empty($error_set)) {
			$response['status'] = (string)$details_xml->$result->$details->$status->State;
			$response['status_detail'] = (string)$details_xml->$result->$details->$status->ReasonCode;
		} else {
			$response['status'] = (string)$details_xml->$result->$details->$status->State;
			$response[$amazon_id] = (string)$details_xml->$result->$details->$amazon_id;
		}

		return $response;
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
		return array('status' => (int)$code, 'ResponseBody' => $responseBody);
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

	public function logger($message) {
		if ($this->config->get('amazon_login_pay_debug') == 1) {
			$log = new Log('amazon_login_pay.log');
			$backtrace = debug_backtrace();
			$log->write('Origin: ' . $backtrace[1]['class'] . '::' . $backtrace[1]['function']);
			$log->write(print_r($message, 1));
		}
	}
}