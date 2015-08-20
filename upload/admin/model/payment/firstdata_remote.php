<?php
class ModelPaymentFirstdataRemote extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "firstdata_remote_order` (
			  `firstdata_remote_order_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `order_id` INT(11) NOT NULL,
			  `order_ref` CHAR(50) NOT NULL,
			  `date_added` DATETIME NOT NULL,
			  `date_modified` DATETIME NOT NULL,
			  `tdate` VARCHAR(30) NOT NULL,
			  `capture_status` INT(1) DEFAULT NULL,
			  `void_status` INT(1) DEFAULT NULL,
			  `refund_status` INT(1) DEFAULT NULL,
			  `currency_code` CHAR(3) NOT NULL,
			  `authcode` VARCHAR(30) NOT NULL,
			  `total` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`firstdata_remote_order_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "firstdata_remote_order_transaction` (
			  `firstdata_remote_order_transaction_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `firstdata_remote_order_id` INT(11) NOT NULL,
			  `date_added` DATETIME NOT NULL,
			  `type` ENUM('auth', 'payment', 'refund', 'void') DEFAULT NULL,
			  `amount` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`firstdata_remote_order_transaction_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "firstdata_remote_card` (
			  `firstdata_remote_card_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `customer_id` INT(11) NOT NULL,
			  `date_added` DATETIME NOT NULL,
			  `digits` CHAR(4) NOT NULL,
			  `expire_month` INT(2) NOT NULL,
			  `expire_year` INT(2) NOT NULL,
			  `card_type` CHAR(15) NOT NULL,
			  `token` CHAR(64) NOT NULL,
			  PRIMARY KEY (`firstdata_remote_card_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "firstdata_remote_order`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "firstdata_remote_order_transaction`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "firstdata_remote_card`;");
	}

	public function call($xml) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://test.ipg-online.com/ipgapi/services");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_HTTPAUTH, 'CURLAUTH_BASIC');
		curl_setopt($ch, CURLOPT_USERPWD, $this->config->get('firstdata_remote_user_id') . ':' . $this->config->get('firstdata_remote_password'));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_CAINFO, $this->config->get('firstdata_remote_ca'));
		curl_setopt($ch, CURLOPT_SSLCERT, $this->config->get('firstdata_remote_certificate'));
		curl_setopt($ch, CURLOPT_SSLKEY, $this->config->get('firstdata_remote_key'));
		curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $this->config->get('firstdata_remote_key_pw'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		//curl_setopt($ch, CURLOPT_STDERR, fopen(DIR_LOGS . "/headers.txt", "w+"));
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		$response = curl_exec ($ch);

		$this->logger('Post data: ' . print_r($this->request->post, 1));
		$this->logger('Request: ' . $xml);
		$this->logger('Curl error #: ' . curl_errno($ch));
		$this->logger('Curl error text: ' . curl_error($ch));
		$this->logger('Curl response info: ' . print_r(curl_getinfo($ch), 1));
		$this->logger('Curl response: ' . $response);

		curl_close ($ch);

		return $response;
	}

	public function void($order_ref, $tdate) {
		$xml = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml .= '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">';
			$xml .= '<SOAP-ENV:Header />';
			$xml .= '<SOAP-ENV:Body>';
			$xml .= '<ipgapi:IPGApiOrderRequest xmlns:v1="http://ipg-online.com/ipgapi/schemas/v1" xmlns:ipgapi="http://ipg-online.com/ipgapi/schemas/ipgapi">';
			$xml .= '<v1:Transaction>';
				$xml .= '<v1:CreditCardTxType>';
					$xml .= '<v1:Type>void</v1:Type>';
				$xml .= '</v1:CreditCardTxType>';
				$xml .= '<v1:TransactionDetails>';
					$xml .= '<v1:OrderId>' . $order_ref . '</v1:OrderId>';
					$xml .= '<v1:TDate>' . $tdate . '</v1:TDate>';
				$xml .= '</v1:TransactionDetails>';
			$xml .= '</v1:Transaction>';
			$xml .= '</ipgapi:IPGApiOrderRequest>';
			$xml .= '</SOAP-ENV:Body>';
		$xml .= '</SOAP-ENV:Envelope>';

		$xml = simplexml_load_string($this->call($xml));

		$xml->registerXPathNamespace('ipgapi', 'http://ipg-online.com/ipgapi/schemas/ipgapi');
		$xml->registerXPathNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');

		$fault = $xml->xpath('//soap:Fault');

		$response['fault'] = '';
		if (!empty($fault[0]) && isset($fault[0]->detail)) {
			$response['fault'] = (string)$fault[0]->detail;
		}

		$string = $xml->xpath('//ipgapi:ErrorMessage');
		$response['error'] = isset($string[0]) ? (string)$string[0] : '';

		$string = $xml->xpath('//ipgapi:TransactionResult');
		$response['transaction_result'] = isset($string[0]) ? (string)$string[0] : '';

		return $response;
	}

	public function updateVoidStatus($firstdata_remote_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "firstdata_remote_order` SET `void_status` = '" . (int)$status . "' WHERE `firstdata_remote_order_id` = '" . (int)$firstdata_remote_order_id . "'");
	}

	public function capture($order_ref, $total, $currency_code) {
		$xml = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml .= '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">';
			$xml .= '<SOAP-ENV:Header />';
			$xml .= '<SOAP-ENV:Body>';
				$xml .= '<ipgapi:IPGApiOrderRequest xmlns:v1="http://ipg-online.com/ipgapi/schemas/v1" xmlns:ipgapi="http://ipg-online.com/ipgapi/schemas/ipgapi">';
					$xml .= '<v1:Transaction>';
						$xml .= '<v1:CreditCardTxType>';
							$xml .= '<v1:Type>postAuth</v1:Type>';
						$xml .= '</v1:CreditCardTxType>';
						$xml .= '<v1:Payment>';
							$xml .= '<v1:ChargeTotal>' . $total . '</v1:ChargeTotal>';
							$xml .= '<v1:Currency>' . $this->mapCurrency($currency_code) . '</v1:Currency>';
						$xml .= '</v1:Payment>';
						$xml .= '<v1:TransactionDetails>';
							$xml .= '<v1:OrderId>' . $order_ref . '</v1:OrderId>';
						$xml .= '</v1:TransactionDetails>';
					$xml .= '</v1:Transaction>';
				$xml .= '</ipgapi:IPGApiOrderRequest>';
			$xml .= '</SOAP-ENV:Body>';
		$xml .= '</SOAP-ENV:Envelope>';

		$xml = simplexml_load_string($this->call($xml));

		$xml->registerXPathNamespace('ipgapi', 'http://ipg-online.com/ipgapi/schemas/ipgapi');
		$xml->registerXPathNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');

		$fault = $xml->xpath('//soap:Fault');

		$response['fault'] = '';
		if (!empty($fault[0]) && isset($fault[0]->detail)) {
			$response['fault'] = (string)$fault[0]->detail;
		}

		$string = $xml->xpath('//ipgapi:ErrorMessage');
		$response['error'] = isset($string[0]) ? (string)$string[0] : '';

		$string = $xml->xpath('//ipgapi:TransactionResult');
		$response['transaction_result'] = isset($string[0]) ? (string)$string[0] : '';

		return $response;
	}

	public function updateCaptureStatus($firstdata_remote_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "firstdata_remote_order` SET `capture_status` = '" . (int)$status . "' WHERE `firstdata_remote_order_id` = '" . (int)$firstdata_remote_order_id . "'");
	}

	public function refund($order_ref, $total, $currency_code) {
		$xml = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml .= '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">';
		$xml .= '<SOAP-ENV:Header />';
		$xml .= '<SOAP-ENV:Body>';
		$xml .= '<ipgapi:IPGApiOrderRequest xmlns:v1="http://ipg-online.com/ipgapi/schemas/v1" xmlns:ipgapi="http://ipg-online.com/ipgapi/schemas/ipgapi">';
		$xml .= '<v1:Transaction>';
		$xml .= '<v1:CreditCardTxType>';
		$xml .= '<v1:Type>return</v1:Type>';
		$xml .= '</v1:CreditCardTxType>';
		$xml .= '<v1:Payment>';
		$xml .= '<v1:ChargeTotal>' . $total . '</v1:ChargeTotal>';
		$xml .= '<v1:Currency>' . $this->mapCurrency($currency_code) . '</v1:Currency>';
		$xml .= '</v1:Payment>';
		$xml .= '<v1:TransactionDetails>';
		$xml .= '<v1:OrderId>' . $order_ref . '</v1:OrderId>';
		$xml .= '</v1:TransactionDetails>';
		$xml .= '</v1:Transaction>';
		$xml .= '</ipgapi:IPGApiOrderRequest>';
		$xml .= '</SOAP-ENV:Body>';
		$xml .= '</SOAP-ENV:Envelope>';

		$xml = simplexml_load_string($this->call($xml));

		$xml->registerXPathNamespace('ipgapi', 'http://ipg-online.com/ipgapi/schemas/ipgapi');
		$xml->registerXPathNamespace('soap', 'http://schemas.xmlsoap.org/soap/envelope/');

		$fault = $xml->xpath('//soap:Fault');

		$response['fault'] = '';
		if (!empty($fault[0]) && isset($fault[0]->detail)) {
			$response['fault'] = (string)$fault[0]->detail;
		}

		$string = $xml->xpath('//ipgapi:ErrorMessage');
		$response['error'] = isset($string[0]) ? (string)$string[0] : '';

		$string = $xml->xpath('//ipgapi:TransactionResult');
		$response['transaction_result'] = isset($string[0]) ? (string)$string[0] : '';

		return $response;
	}

	public function updateRefundStatus($firstdata_remote_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "firstdata_remote_order` SET `refund_status` = '" . (int)$status . "' WHERE `firstdata_remote_order_id` = '" . (int)$firstdata_remote_order_id . "'");
	}

	public function getOrder($order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "firstdata_remote_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($qry->num_rows) {
			$order = $qry->row;
			$order['transactions'] = $this->getTransactions($order['firstdata_remote_order_id']);

			return $order;
		} else {
			return false;
		}
	}

	private function getTransactions($firstdata_remote_order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "firstdata_remote_order_transaction` WHERE `firstdata_remote_order_id` = '" . (int)$firstdata_remote_order_id . "'");

		if ($qry->num_rows) {
			return $qry->rows;
		} else {
			return false;
		}
	}

	public function addTransaction($firstdata_remote_order_id, $type, $total) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "firstdata_remote_order_transaction` SET `firstdata_remote_order_id` = '" . (int)$firstdata_remote_order_id . "', `date_added` = now(), `type` = '" . $this->db->escape($type) . "', `amount` = '" . (float)$total . "'");
	}

	public function logger($message) {
		if ($this->config->get('firstdata_remote_debug') == 1) {
			$log = new Log('firstdata_remote.log');
			$log->write($message);
		}
	}

	public function getTotalCaptured($firstdata_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "firstdata_remote_order_transaction` WHERE `firstdata_remote_order_id` = '" . (int)$firstdata_order_id . "' AND (`type` = 'payment' OR `type` = 'refund')");

		return (float)$query->row['total'];
	}

	public function getTotalRefunded($firstdata_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "firstdata_remote_order_transaction` WHERE `firstdata_remote_order_id` = '" . (int)$firstdata_order_id . "' AND 'refund'");

		return (float)$query->row['total'];
	}

	public function mapCurrency($code) {
		$currency = array(
			'GBP' => 826,
			'USD' => 840,
			'EUR' => 978,
		);

		if (array_key_exists($code, $currency)) {
			return $currency[$code];
		} else {
			return false;
		}
	}
}