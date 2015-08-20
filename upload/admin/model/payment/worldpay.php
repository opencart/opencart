<?php

class ModelPaymentWorldpay extends Model {

	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "worldpay_order` (
			  `worldpay_order_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `order_id` INT(11) NOT NULL,
			  `order_code` VARCHAR(50),
			  `date_added` DATETIME NOT NULL,
			  `date_modified` DATETIME NOT NULL,
			  `refund_status` INT(1) DEFAULT NULL,
			  `currency_code` CHAR(3) NOT NULL,
			  `total` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`worldpay_order_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "worldpay_order_transaction` (
			  `worldpay_order_transaction_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `worldpay_order_id` INT(11) NOT NULL,
			  `date_added` DATETIME NOT NULL,
			  `type` ENUM('payment', 'refund') DEFAULT NULL,
			  `amount` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`worldpay_order_transaction_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "worldpay_order_recurring` (
			  `worldpay_order_recurring_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `order_id` INT(11) NOT NULL,
			  `order_recurring_id` INT(11) NOT NULL,
			  `order_code` VARCHAR(50),
			  `token` VARCHAR(50),
			  `date_added` DATETIME NOT NULL,
			  `date_modified` DATETIME NOT NULL,
			  `next_payment` DATETIME NOT NULL,
			  `trial_end` datetime DEFAULT NULL,
			  `subscription_end` datetime DEFAULT NULL,
			  `currency_code` CHAR(3) NOT NULL,
			  `total` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`worldpay_order_recurring_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "worldpay_card` (
			  `card_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `customer_id` INT(11) NOT NULL,
			  `order_id` INT(11) NOT NULL,
			  `token` VARCHAR(50) NOT NULL,
			  `digits` VARCHAR(22) NOT NULL,
			  `expiry` VARCHAR(5) NOT NULL,
			  `type` VARCHAR(50) NOT NULL,
			  PRIMARY KEY (`card_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "worldpay_order`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "worldpay_order_transaction`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "worldpay_order_recurring`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "worldpay_card`;");
	}

	public function refund($order_id, $amount) {
		$worldpay_order = $this->getOrder($order_id);

		if (!empty($worldpay_order) && $worldpay_order['refund_status'] != 1) {
			$order['refundAmount'] = (int)($amount * 100);

			$url = $worldpay_order['order_code'] . '/refund';

			$response_data = $this->sendCurl($url, $order);

			return $response_data;
		} else {
			return false;
		}
	}

	public function updateRefundStatus($worldpay_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "worldpay_order` SET `refund_status` = '" . (int)$status . "' WHERE `worldpay_order_id` = '" . (int)$worldpay_order_id . "'");
	}

	public function getOrder($order_id) {

		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "worldpay_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($qry->num_rows) {
			$order = $qry->row;
			$order['transactions'] = $this->getTransactions($order['worldpay_order_id'], $qry->row['currency_code']);

			return $order;
		} else {
			return false;
		}
	}

	private function getTransactions($worldpay_order_id, $currency_code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "worldpay_order_transaction` WHERE `worldpay_order_id` = '" . (int)$worldpay_order_id . "'");

		$transactions = array();
		if ($query->num_rows) {
			foreach ($query->rows as $row) {
				$row['amount'] = $this->currency->format($row['amount'], $currency_code, false);
				$transactions[] = $row;
			}
			return $transactions;
		} else {
			return false;
		}
	}

	public function addTransaction($worldpay_order_id, $type, $total) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "worldpay_order_transaction` SET `worldpay_order_id` = '" . (int)$worldpay_order_id . "', `date_added` = now(), `type` = '" . $this->db->escape($type) . "', `amount` = '" . (double)$total . "'");
	}

	public function getTotalReleased($worldpay_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "worldpay_order_transaction` WHERE `worldpay_order_id` = '" . (int)$worldpay_order_id . "' AND (`type` = 'payment' OR `type` = 'refund')");

		return (double)$query->row['total'];
	}

	public function getTotalRefunded($worldpay_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "worldpay_order_transaction` WHERE `worldpay_order_id` = '" . (int)$worldpay_order_id . "' AND 'refund'");

		return (double)$query->row['total'];
	}

	public function sendCurl($url, $order) {

		$json = json_encode($order);

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, 'https://api.worldpay.com/v1/orders/' . $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt(
				$curl, CURLOPT_HTTPHEADER, array(
			"Authorization: " . $this->config->get('worldpay_service_key'),
			"Content-Type: application/json",
			"Content-Length: " . strlen($json)
				)
		);

		$result = json_decode(curl_exec($curl));
		curl_close($curl);

		$response = array();

		if (isset($result)) {
			$response['status'] = $result->httpStatusCode;
			$response['message'] = $result->message;
			$response['full_details'] = $result;
		} else {
			$response['status'] = 'success';
		}

		return $response;
	}

	public function logger($message) {
		if ($this->config->get('worldpay_debug') == 1) {
			$log = new Log('worldpay.log');
			$log->write($message);
		}
	}

}
