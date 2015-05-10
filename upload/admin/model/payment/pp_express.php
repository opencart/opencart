<?php
class ModelPaymentPPExpress extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "paypal_order` (
			  `paypal_order_id` int(11) NOT NULL AUTO_INCREMENT,
			  `order_id` int(11) NOT NULL,
			  `date_added` DATETIME NOT NULL,
			  `date_modified` DATETIME NOT NULL,
			  `capture_status` ENUM('Complete','NotComplete') DEFAULT NULL,
			  `currency_code` CHAR(3) NOT NULL,
			  `authorization_id` VARCHAR(30) NOT NULL,
			  `total` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`paypal_order_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "paypal_order_transaction` (
			  `paypal_order_transaction_id` int(11) NOT NULL AUTO_INCREMENT,
			  `paypal_order_id` int(11) NOT NULL,
			  `transaction_id` CHAR(20) NOT NULL,
			  `parent_transaction_id` CHAR(20) NOT NULL,
			  `date_added` DATETIME NOT NULL,
			  `note` VARCHAR(255) NOT NULL,
			  `msgsubid` CHAR(38) NOT NULL,
			  `receipt_id` CHAR(20) NOT NULL,
			  `payment_type` ENUM('none','echeck','instant', 'refund', 'void') DEFAULT NULL,
			  `payment_status` CHAR(20) NOT NULL,
			  `pending_reason` CHAR(50) NOT NULL,
			  `transaction_entity` CHAR(50) NOT NULL,
			  `amount` DECIMAL( 10, 2 ) NOT NULL,
			  `debug_data` TEXT NOT NULL,
			  `call_data` TEXT NOT NULL,
			  PRIMARY KEY (`paypal_order_transaction_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "paypal_order_transaction`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "paypal_order`;");
	}

	public function totalCaptured($paypal_order_id) {
		$qry = $this->db->query("SELECT SUM(`amount`) AS `amount` FROM `" . DB_PREFIX . "paypal_order_transaction` WHERE `paypal_order_id` = '" . (int)$paypal_order_id . "' AND `pending_reason` != 'authorization' AND (`payment_status` = 'Partially-Refunded' OR `payment_status` = 'Completed' OR `payment_status` = 'Pending') AND `transaction_entity` = 'payment'");

		return $qry->row['amount'];
	}

	public function totalRefundedOrder($paypal_order_id) {
		$qry = $this->db->query("SELECT SUM(`amount`) AS `amount` FROM `" . DB_PREFIX . "paypal_order_transaction` WHERE `paypal_order_id` = '" . (int)$paypal_order_id . "' AND `payment_status` = 'Refunded'");

		return $qry->row['amount'];
	}

	public function totalRefundedTransaction($transaction_id) {
		$qry = $this->db->query("SELECT SUM(`amount`) AS `amount` FROM `" . DB_PREFIX . "paypal_order_transaction` WHERE `parent_transaction_id` = '" . $this->db->escape($transaction_id) . "' AND `payment_type` = 'refund'");

		return $qry->row['amount'];
	}

	public function log($data, $title = null) {
		if ($this->config->get('pp_express_debug')) {
			$this->log->write('PayPal Express debug (' . $title . '): ' . json_encode($data));
		}
	}

	public function getOrder($order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "paypal_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($qry->num_rows) {
			$order = $qry->row;
			$order['transactions'] = $this->getTransactions($order['paypal_order_id']);
			$order['captured'] = $this->totalCaptured($order['paypal_order_id']);
			return $order;
		} else {
			return false;
		}
	}

	public function updateOrder($capture_status, $order_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "paypal_order` SET `date_modified` = now(), `capture_status` = '" . $this->db->escape($capture_status) . "' WHERE `order_id` = '" . (int)$order_id . "'");
	}

	public function addTransaction($transaction_data, $request_data = array()) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "paypal_order_transaction` SET `paypal_order_id` = '" . (int)$transaction_data['paypal_order_id'] . "', `transaction_id` = '" . $this->db->escape($transaction_data['transaction_id']) . "', `parent_transaction_id` = '" . $this->db->escape($transaction_data['parent_transaction_id']) . "', `date_added` = NOW(), `note` = '" . $this->db->escape($transaction_data['note']) . "', `msgsubid` = '" . $this->db->escape($transaction_data['msgsubid']) . "', `receipt_id` = '" . $this->db->escape($transaction_data['receipt_id']) . "', `payment_type` = '" . $this->db->escape($transaction_data['payment_type']) . "', `payment_status` = '" . $this->db->escape($transaction_data['payment_status']) . "', `pending_reason` = '" . $this->db->escape($transaction_data['pending_reason']) . "', `transaction_entity` = '" . $this->db->escape($transaction_data['transaction_entity']) . "', `amount` = '" . (float)$transaction_data['amount'] . "', `debug_data` = '" . $this->db->escape($transaction_data['debug_data']) . "'");

		$paypal_order_transaction_id = $this->db->getLastId();

		if ($request_data) {
			$serialized_data = serialize($request_data);

			$this->db->query("
				UPDATE " . DB_PREFIX . "paypal_order_transaction
				SET call_data = '" . $this->db->escape($serialized_data) . "'
				WHERE paypal_order_transaction_id = " . (int)$paypal_order_transaction_id . "
				LIMIT 1
			");
		}

		return $paypal_order_transaction_id;
	}

	public function getFailedTransaction($paypal_order_transaction_id) {
		$result = $this->db->query("
			SELECT *
			FROM " . DB_PREFIX . "paypal_order_transaction
			WHERE paypal_order_transaction_id = " . (int)$paypal_order_transaction_id . "
		")->row;

		if ($result) {
			return $result;
		} else {
			return false;
		}
	}

	public function updateTransaction($transaction) {
		$this->db->query("
			UPDATE " . DB_PREFIX . "paypal_order_transaction
			SET paypal_order_id = " . (int)$transaction['paypal_order_id'] . ",
				transaction_id = '" . $this->db->escape($transaction['transaction_id']) . "',
				parent_transaction_id = '" . $this->db->escape($transaction['parent_transaction_id']) . "',
				date_added = '" . $this->db->escape($transaction['date_added']) . "',
				note = '" . $this->db->escape($transaction['note']) . "',
				msgsubid = '" . $this->db->escape($transaction['msgsubid']) . "',
				receipt_id = '" . $this->db->escape($transaction['receipt_id']) . "',
				payment_type = '" . $this->db->escape($transaction['payment_type']) . "',
				payment_status = '" . $this->db->escape($transaction['payment_status']) . "',
				pending_reason = '" . $this->db->escape($transaction['pending_reason']) . "',
				transaction_entity = '" . $this->db->escape($transaction['transaction_entity']) . "',
				amount = '" . $this->db->escape($transaction['amount']) . "',
				debug_data = '" . $this->db->escape($transaction['debug_data']) . "',
				call_data = '" . $this->db->escape($transaction['call_data']) . "'
			WHERE paypal_order_transaction_id = " . (int)$transaction['paypal_order_transaction_id'] . "
		");
	}

	private function getTransactions($paypal_order_id) {
		$qry = $this->db->query("SELECT `ot`.*, (SELECT count(`ot2`.`paypal_order_id`) FROM `" . DB_PREFIX . "paypal_order_transaction` `ot2` WHERE `ot2`.`parent_transaction_id` = `ot`.`transaction_id` ) AS `children` FROM `" . DB_PREFIX . "paypal_order_transaction` `ot` WHERE `paypal_order_id` = '" . (int)$paypal_order_id . "'");

		if ($qry->num_rows) {
			return $qry->rows;
		} else {
			return false;
		}
	}

	public function getLocalTransaction($transaction_id) {
		$result = $this->db->query("
			SELECT *
			FROM " . DB_PREFIX . "paypal_order_transaction
			WHERE transaction_id = '" . $this->db->escape($transaction_id) . "'
		")->row;

		if ($result) {
			return $result;
		} else {
			return false;
		}
	}

	public function getTransaction($transaction_id) {
		$call_data = array(
			'METHOD' => 'GetTransactionDetails',
			'TRANSACTIONID' => $transaction_id,
		);

		return $this->call($call_data);
	}

	public function cleanReturn($data) {
		$data = explode('&', $data);

		$arr = array();

		foreach ($data as $k => $v) {
			$tmp = explode('=', $v);
			$arr[$tmp[0]] = urldecode($tmp[1]);
		}

		return $arr;
	}

	public function call($data) {

		if ($this->config->get('pp_express_test') == 1) {
			$api_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
			$user = $this->config->get('pp_express_sandbox_username');
			$password = $this->config->get('pp_express_sandbox_password');
			$signature = $this->config->get('pp_express_sandbox_signature');
		} else {
			$api_endpoint = 'https://api-3t.paypal.com/nvp';
			$user = $this->config->get('pp_express_username');
			$password = $this->config->get('pp_express_password');
			$signature = $this->config->get('pp_express_signature');
		}

		$settings = array(
			'USER' => $user,
			'PWD' => $password,
			'SIGNATURE' => $signature,
			'VERSION' => '84',
			'BUTTONSOURCE' => 'OpenCart_Cart_EC',
		);

		$this->log($data, 'Call data');

		$defaults = array(
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_URL => $api_endpoint,
			CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1",
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FORBID_REUSE => 1,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_POSTFIELDS => http_build_query(array_merge($data, $settings), '', "&")
		);

		$ch = curl_init();

		curl_setopt_array($ch, $defaults);

		if (!$result = curl_exec($ch)) {

			$log_data = array(
				'curl_error' => curl_error($ch),
				'curl_errno' => curl_errno($ch)
			);

			$this->log($log_data, 'CURL failed');
			return false;
		}

		$this->log($result, 'Result');

		curl_close($ch);

		return $this->cleanReturn($result);
	}

	public function getOrderId($transaction_id) {
		$qry = $this->db->query("SELECT `o`.`order_id` FROM `" . DB_PREFIX . "paypal_order_transaction` `ot` LEFT JOIN `" . DB_PREFIX . "paypal_order` `o`  ON `o`.`paypal_order_id` = `ot`.`paypal_order_id`  WHERE `ot`.`transaction_id` = '" . $this->db->escape($transaction_id) . "' LIMIT 1");

		if ($qry->num_rows) {
			return $qry->row['order_id'];
		} else {
			return false;
		}
	}

	public function currencyCodes() {
		return array(
			'AUD',
			'BRL',
			'CAD',
			'CZK',
			'DKK',
			'EUR',
			'HKD',
			'HUF',
			'ILS',
			'JPY',
			'MYR',
			'MXN',
			'NOK',
			'NZD',
			'PHP',
			'PLN',
			'GBP',
			'SGD',
			'SEK',
			'CHF',
			'TWD',
			'THB',
			'TRY',
			'USD',
		);
	}

	public function recurringCancel($ref) {

		$data = array(
			'METHOD' => 'ManageRecurringPaymentsProfileStatus',
			'PROFILEID' => $ref,
			'ACTION' => 'Cancel'
		);

		return $this->call($data);
	}

	public function getTokens($test) {
		if ($test == 'sandbox') {
			$endpoint = 'https://api.sandbox.paypal.com/v1/oauth2/token';
			$client_id = 'Ad3QTBAHwhuNI_blejO4_RqvES74yWRUC61c5QVNDbxkq9csbLpDZogWp_0n';
			$client_secret = 'EGqgGxCqjs1GIa5l1Ex_Flq0Mb2oMT3rJu2kwz6FuF9QKyxCg6qNqyddxCCW';
		} else {
			$endpoint = 'https://api.paypal.com/v1/oauth2/token';
			$client_id = 'AWyAiBCUYsE156N8YpiiISQpSpep2HPoXXPrf33VBeYleE0SQJg40pgEqZvq';
			$client_secret = 'EEkc6xB30fDkgUO_YldWWHxKDquY7LBRId6FJ-parAR1CsVpK35zB6U0SIh4';
		}

		$request = '';
		$request .= 'client_id=' . $client_id;
		$request .= '&client_secret=' . $client_secret;
		$request .= '&grant_type=client_credentials';

		$additional_opts = array(
			CURLOPT_USERPWD => $client_id . ':' . $client_secret,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $request
		);

		$curl = $this->curl($endpoint, $additional_opts);

		$this->log('cURL Response 1: ' . print_r($curl, 1));

		return $curl;
	}

	public function getUserInfo($merchant_id, $test, $access_token) {
		if ($test == 'sandbox') {
			$endpoint = 'https://api.sandbox.paypal.com/v1/customer/partners/T4E8WSXT43QPJ/merchant-integrations';
		} else {
			$endpoint = 'https://api.paypal.com/v1/customer/partners/9PDNYE4RZBVFJ/merchant-integrations';
		}

		$endpoint1 = $endpoint . '?tracking_id=' . $merchant_id;

		$header = array();
		$header[] = 'Content-Type: application/json';
		$header[] = 'Authorization: Bearer ' . $access_token;
		$header[] = 'PAYPAL_SERVICE_VERSION:1.2.0';

		$additional_opts = array(
			CURLOPT_HTTPHEADER => $header,
		);

		$curl = $this->curl($endpoint1, $additional_opts);

		$this->log('cURL Response 2: ' . print_r($curl, 1));

		if (isset($curl->merchant_id)) {
			$endpoint2 = $endpoint . '/' . $curl->merchant_id;
			$curl2 = $this->curl($endpoint2, $additional_opts);

			$this->log('cURL Response 3: ' . print_r($curl2, 1));

			if (isset($curl2->api_credentials->signature)) {
				return $curl2->api_credentials->signature;
			} else {
				return;
			}
		} else {
			return;
		}
	}

	private function curl($endpoint, $additional_opts = array()) {
		$default_opts = array(
			CURLOPT_PORT => 443,
			CURLOPT_HEADER => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FORBID_REUSE => 1,
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_URL => $endpoint,
		);

		$ch = curl_init($endpoint);

		$opts = $default_opts + $additional_opts;

		curl_setopt_array($ch, $opts);

		$response = json_decode(curl_exec($ch));

		curl_close($ch);

		return $response;
	}
}