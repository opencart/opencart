<?php
class ModelExtensionPaymentSecureTradingWs extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "securetrading_ws_order` (
			  `securetrading_ws_order_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `order_id` INT(11) NOT NULL,
			  `md` varchar(1024) DEFAULT NULL,
			  `transaction_reference` varchar(127) DEFAULT NULL,
			  `created` DATETIME NOT NULL,
			  `modified` DATETIME NOT NULL,
			  `release_status` INT(1) DEFAULT NULL,
			  `void_status` INT(1) DEFAULT NULL,
			  `settle_type` INT(1) DEFAULT NULL,
			  `rebate_status` INT(1) DEFAULT NULL,
			  `currency_code` CHAR(3) NOT NULL,
			  `total` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`securetrading_ws_order_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "securetrading_ws_order_transaction` (
			  `securetrading_ws_order_transaction_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `securetrading_ws_order_id` INT(11) NOT NULL,
			  `created` DATETIME NOT NULL,
			  `type` ENUM('auth', 'payment', 'rebate', 'reversed') DEFAULT NULL,
			  `amount` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`securetrading_ws_order_transaction_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "securetrading_ws_order");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "securetrading_ws_order_transaction`;");
	}

	public function void($order_id) {
		$securetrading_ws_order = $this->getOrder($order_id);

		if (!empty($securetrading_ws_order) && $securetrading_ws_order['release_status'] == 0) {

			$requestblock_xml = new SimpleXMLElement('<requestblock></requestblock>');
			$requestblock_xml->addAttribute('version', '3.67');
			$requestblock_xml->addChild('alias', $this->config->get('securetrading_ws_username'));

			$request_node = $requestblock_xml->addChild('request');
			$request_node->addAttribute('type', 'TRANSACTIONUPDATE');

			$filter_node = $request_node->addChild('filter');
			$filter_node->addChild('sitereference', $this->config->get('securetrading_ws_site_reference'));
			$filter_node->addChild('transactionreference', $securetrading_ws_order['transaction_reference']);

			$request_node->addChild('updates')->addChild('settlement')->addChild('settlestatus', 3);

			return $this->call($requestblock_xml->asXML());
		} else {
			return false;
		}
	}

	public function updateVoidStatus($securetrading_ws_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "securetrading_ws_order` SET `void_status` = '" . (int)$status . "' WHERE `securetrading_ws_order_id` = '" . (int)$securetrading_ws_order_id . "'");
	}

	public function release($order_id, $amount) {
		$securetrading_ws_order = $this->getOrder($order_id);
		$total_released = $this->getTotalReleased($securetrading_ws_order['securetrading_ws_order_id']);

		if (!empty($securetrading_ws_order) && $securetrading_ws_order['release_status'] == 0 && $total_released <= $amount) {

			$requestblock_xml = new SimpleXMLElement('<requestblock></requestblock>');
			$requestblock_xml->addAttribute('version', '3.67');
			$requestblock_xml->addChild('alias', $this->config->get('securetrading_ws_username'));

			$request_node = $requestblock_xml->addChild('request');
			$request_node->addAttribute('type', 'TRANSACTIONUPDATE');

			$filter_node = $request_node->addChild('filter');
			$filter_node->addChild('sitereference', $this->config->get('securetrading_ws_site_reference'));
			$filter_node->addChild('transactionreference', $securetrading_ws_order['transaction_reference']);

			$settlement_node = $request_node->addChild('updates')->addChild('settlement');
			$settlement_node->addChild('settlestatus', 0);
			$settlement_node->addChild('settlemainamount', $amount)->addAttribute('currencycode', $securetrading_ws_order['currency_code']);

			return $this->call($requestblock_xml->asXML());
		} else {
			return false;
		}
	}

	public function updateReleaseStatus($securetrading_ws_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "securetrading_ws_order` SET `release_status` = '" . (int)$status . "' WHERE `securetrading_ws_order_id` = '" . (int)$securetrading_ws_order_id . "'");
	}

	public function updateForRebate($securetrading_ws_order_id, $order_ref) {
		$this->db->query("UPDATE `" . DB_PREFIX . "securetrading_ws_order` SET `order_ref_previous` = '_multisettle_" . $this->db->escape($order_ref) . "' WHERE `securetrading_ws_order_id` = '" . (int)$securetrading_ws_order_id . "' LIMIT 1");
	}

	public function rebate($order_id, $refunded_amount) {
		$securetrading_ws_order = $this->getOrder($order_id);

		if (!empty($securetrading_ws_order) && $securetrading_ws_order['rebate_status'] != 1) {

			$requestblock_xml = new SimpleXMLElement('<requestblock></requestblock>');
			$requestblock_xml->addAttribute('version', '3.67');
			$requestblock_xml->addChild('alias', $this->config->get('securetrading_ws_username'));

			$request_node = $requestblock_xml->addChild('request');
			$request_node->addAttribute('type', 'REFUND');

			$request_node->addChild('merchant')->addChild('orderreference', $order_id);

			$operation_node = $request_node->addChild('operation');
			$operation_node->addChild('accounttypedescription', 'ECOM');
			$operation_node->addChild('parenttransactionreference', $securetrading_ws_order['transaction_reference']);
			$operation_node->addChild('sitereference', $this->config->get('securetrading_ws_site_reference'));

			$billing_node = $request_node->addChild('billing');
			$billing_node->addAttribute('currencycode', $securetrading_ws_order['currency_code']);
			$billing_node->addChild('amount', str_replace('.', '', $refunded_amount));

			return $this->call($requestblock_xml->asXML());
		} else {
			return false;
		}
	}

	public function getOrder($order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "securetrading_ws_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($qry->num_rows) {
			$order = $qry->row;
			$order['transactions'] = $this->getTransactions($order['securetrading_ws_order_id']);

			return $order;
		} else {
			return false;
		}
	}

	private function getTransactions($securetrading_ws_order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "securetrading_ws_order_transaction` WHERE `securetrading_ws_order_id` = '" . (int)$securetrading_ws_order_id . "'");

		if ($qry->num_rows) {
			return $qry->rows;
		} else {
			return false;
		}
	}

	public function addTransaction($securetrading_ws_order_id, $type, $total) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "securetrading_ws_order_transaction` SET `securetrading_ws_order_id` = '" . (int)$securetrading_ws_order_id . "', `created` = now(), `type` = '" . $this->db->escape($type) . "', `amount` = '" . (double)$total . "'");
	}

	public function getTotalReleased($securetrading_ws_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "securetrading_ws_order_transaction` WHERE `securetrading_ws_order_id` = '" . (int)$securetrading_ws_order_id . "' AND (`type` = 'payment' OR `type` = 'rebate')");

		return (double)$query->row['total'];
	}

	public function getTotalRebated($securetrading_ws_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "securetrading_ws_order_transaction` WHERE `securetrading_ws_order_id` = '" . (int)$securetrading_ws_order_id . "' AND 'rebate'");

		return (double)$query->row['total'];
	}

	public function increaseRefundedAmount($order_id, $amount) {
		$this->db->query("UPDATE " . DB_PREFIX . "securetrading_ws_order SET refunded = refunded + " . (double)$amount . " WHERE order_id = " . (int)$order_id);
	}

	public function getCsv($data) {
		$ch = curl_init();

		$post_data = array();
		$post_data['sitereferences'] = $this->config->get('securetrading_ws_site_reference');
		$post_data['startdate'] = $data['date_from'];
		$post_data['enddate'] = $data['date_to'];
		$post_data['accounttypedescriptions'] = 'ECOM';

		if ($data['detail']) {
			$post_data['optionalfields'] = array(
				'parenttransactionreference',
				'accounttypedescription',
				'requesttypedescription',
				'mainamount',
				'currencyiso3a',
				'errorcode',
				'authcode',
				'customerip',
				'fraudrating',
				'orderreference',
				'paymenttypedescription',
				'maskedpan',
				'expirydate',
				'settlestatus',
				'settlemainamount',
				'settleduedate',
				'securityresponsesecuritycode',
				'securityresponseaddress',
				'securityresponsepostcode',
				'billingprefixname',
				'billingfirstname',
				'billingmiddlename',
				'billinglastname',
				'billingpremise',
				'billingstreet',
				'billingtown',
				'billingcounty',
				'billingemail',
				'billingcountryiso2a',
				'billingpostcode',
				'billingtelephones',
				'customerprefixname',
				'customerfirstname',
				'customermiddlename',
				'customerlastname',
				'customerpremise',
				'customerstreet',
				'customertown',
				'customercounty',
				'customeremail',
				'customercountryiso2a',
				'customerpostcode',
				'customertelephones',
			);
		} else {
			$post_data['optionalfields'] = array(
				'orderreference',
				'currencyiso3a',
				'errorcode',
				'paymenttypedescription',
				'settlestatus',
				'requesttypedescription',
				'mainamount',
				'billingfirstname',
				'billinglastname',
			);
		}

		if (isset($data['currency']) && !empty($data['currency'])) {
			$post_data['currencyiso3as'] = $data['currency'];
		}

		if (isset($data['status']) && !empty($data['status'])) {
			$post_data['errorcodes'] = $data['status'];
		}

		if (isset($data['payment_type']) && !empty($data['payment_type'])) {
			$post_data['paymenttypedescriptions'] = $data['payment_type'];
		}

		if (isset($data['request']) && !empty($data['request'])) {
			$post_data['requesttypedescriptions'] = $data['request'];
		}

		if (isset($data['settle_status']) && !empty($data['settle_status'])) {
			$post_data['settlestatuss'] = $data['settle_status'];
		}

		$defaults = array(
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_URL => 'https://myst.securetrading.net/auto/transactions/transactionsearch',
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FORBID_REUSE => 1,
			CURLOPT_TIMEOUT => 15,
			CURLOPT_HTTPHEADER => array(
				'User-Agent: OpenCart - Secure Trading WS',
				'Authorization: Basic ' . base64_encode($this->config->get('securetrading_ws_csv_username') . ':' . $this->config->get('securetrading_ws_csv_password')),
			),
			CURLOPT_POSTFIELDS => $this->encodePost($post_data),
		);

		curl_setopt_array($ch, $defaults);

		$response = curl_exec($ch);

		if ($response === false) {
			$this->log->write('Secure Trading WS CURL Error: (' . curl_errno($ch) . ') ' . curl_error($ch));
		}

		curl_close($ch);

		if (empty($response) || $response === 'No records found for search') {
			return false;
		}

		if (preg_match('/401 Authorization Required/', $response)) {
			return false;
		}

		return $response;
	}

	private function encodePost($data) {
		$params = array();

		foreach ($data as $key => $value) {
			if (is_array($value)) {
				foreach ($value as $v) {
					$params[] = $key . '=' . rawurlencode($v);
				}
			} else {
				$params[] = $key . '=' . rawurlencode($value);
			}
		}

		return implode('&', $params);
	}

	public function call($data) {
		$ch = curl_init();

		$defaults = array(
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_URL => 'https://webservices.securetrading.net/xml/',
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FORBID_REUSE => 1,
			CURLOPT_TIMEOUT => 15,
			CURLOPT_HTTPHEADER => array(
				'User-Agent: OpenCart - Secure Trading WS',
				'Content-Length: ' . strlen($data),
				'Authorization: Basic ' . base64_encode($this->config->get('securetrading_ws_username') . ':' . $this->config->get('securetrading_ws_password')),
			),
			CURLOPT_POSTFIELDS => $data,
		);

		curl_setopt_array($ch, $defaults);

		$response = curl_exec($ch);

		if ($response === false) {
			$this->log->write('Secure Trading WS CURL Error: (' . curl_errno($ch) . ') ' . curl_error($ch));
		}

		curl_close($ch);

		return $response;
	}

	public function logger($message) {
		$log = new Log('securetrading_ws.log');
		$log->write($message);
	}
}