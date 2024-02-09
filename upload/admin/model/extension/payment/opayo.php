<?php
class ModelExtensionPaymentOpayo extends Model {
	
	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "opayo_order` (
			  `opayo_order_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `order_id` INT(11) NOT NULL,
			  `VPSTxId` VARCHAR(50),
			  `VendorTxCode` VARCHAR(50) NOT NULL,
			  `SecurityKey` CHAR(50) NOT NULL,
			  `TxAuthNo` INT(50),
			  `date_added` DATETIME NOT NULL,
			  `date_modified` DATETIME NOT NULL,
			  `release_status` INT(1) DEFAULT NULL,
			  `void_status` INT(1) DEFAULT NULL,
			  `settle_type` INT(1) DEFAULT NULL,
			  `rebate_status` INT(1) DEFAULT NULL,
			  `currency_code` CHAR(3) NOT NULL,
			  `total` DECIMAL( 10, 2 ) NOT NULL,
			  `card_id` INT(11),
			  PRIMARY KEY (`opayo_order_id`),
			  KEY (`order_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "opayo_order_transaction` (
			  `opayo_order_transaction_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `opayo_order_id` INT(11) NOT NULL,
			  `date_added` DATETIME NOT NULL,
			  `type` ENUM('auth', 'payment', 'rebate', 'void') DEFAULT NULL,
			  `amount` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`opayo_order_transaction_id`),
			  KEY (`opayo_order_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "opayo_order_recurring` (
			  `opayo_order_recurring_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `order_id` INT(11) NOT NULL,
			  `order_recurring_id` INT(11) NOT NULL,
			  `VPSTxId` VARCHAR(50),
			  `VendorTxCode` VARCHAR(50) NOT NULL,
			  `SecurityKey` CHAR(50) NOT NULL,
			  `TxAuthNo` INT(50),
			  `date_added` DATETIME NOT NULL,
			  `date_modified` DATETIME NOT NULL,
			  `next_payment` DATETIME NOT NULL,
			  `trial_end` DATETIME DEFAULT NULL,
			  `subscription_end` DATETIME DEFAULT NULL,
			  `currency_code` CHAR(3) NOT NULL,
			  `total` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`opayo_order_recurring_id`),
			  KEY (`order_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
		
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "opayo_card` (
			  `card_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `customer_id` INT(11) NOT NULL,
			  `token` VARCHAR(50) NOT NULL,
			  `digits` VARCHAR(4) NOT NULL,
			  `expiry` VARCHAR(5) NOT NULL,
			  `type` VARCHAR(50) NOT NULL,
			  PRIMARY KEY (`card_id`),
			  KEY (`customer_id`),
			  KEY (`token`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "opayo_order`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "opayo_order_transaction`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "opayo_order_recurring`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "opayo_card`;");
	}

	public function void($order_id) {
		$opayo_order = $this->getOrder($order_id);

		if (!empty($opayo_order) && $opayo_order['release_status'] == 0) {
			$void_data = array();
			
			// Setting
			$_config = new Config();
			$_config->load('opayo');
			
			$config_setting = $_config->get('opayo_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_opayo_setting'));

			if ($setting['general']['environment'] == 'live') {
				$url = 'https://live.opayo.eu.elavon.com/gateway/service/void.vsp';
				$void_data['VPSProtocol'] = '4.00';
			} elseif ($setting['general']['environment'] == 'test') {
				$url = 'https://sandbox.opayo.eu.elavon.com/gateway/service/void.vsp';
				$void_data['VPSProtocol'] = '4.00';
			}

			$void_data['TxType'] = 'VOID';
			$void_data['Vendor'] = $this->config->get('payment_opayo_vendor');
			$void_data['VendorTxCode'] = $opayo_order['VendorTxCode'];
			$void_data['VPSTxId'] = $opayo_order['VPSTxId'];
			$void_data['SecurityKey'] = $opayo_order['SecurityKey'];
			$void_data['TxAuthNo'] = $opayo_order['TxAuthNo'];

			$response_data = $this->sendCurl($url, $void_data);

			return $response_data;
		} else {
			return false;
		}
	}

	public function updateVoidStatus($opayo_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "opayo_order` SET `void_status` = '" . (int)$status . "' WHERE `opayo_order_id` = '" . (int)$opayo_order_id . "'");
	}

	public function release($order_id, $amount) {
		$opayo_order = $this->getOrder($order_id);
		$total_released = $this->getTotalReleased($opayo_order['opayo_order_id']);

		if (!empty($opayo_order) && $opayo_order['release_status'] == 0 && ($total_released + $amount <= $opayo_order['total'])) {
			$release_data = array();

			// Setting
			$_config = new Config();
			$_config->load('opayo');
			
			$config_setting = $_config->get('opayo_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_opayo_setting'));

			if ($setting['general']['environment'] == 'live') {
				$url = 'https://live.opayo.eu.elavon.com/gateway/service/release.vsp';
				$release_data['VPSProtocol'] = '4.00';
			} elseif ($setting['general']['environment'] == 'test') {
				$url = 'https://sandbox.opayo.eu.elavon.com/gateway/service/release.vsp';
				$release_data['VPSProtocol'] = '4.00';
			}

			$release_data['TxType'] = 'RELEASE';
			$release_data['Vendor'] = $this->config->get('payment_opayo_vendor');
			$release_data['VendorTxCode'] = $opayo_order['VendorTxCode'];
			$release_data['VPSTxId'] = $opayo_order['VPSTxId'];
			$release_data['SecurityKey'] = $opayo_order['SecurityKey'];
			$release_data['TxAuthNo'] = $opayo_order['TxAuthNo'];
			$release_data['Amount'] = $amount;

			$response_data = $this->sendCurl($url, $release_data);

			return $response_data;
		} else {
			return false;
		}
	}

	public function updateReleaseStatus($opayo_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "opayo_order` SET `release_status` = '" . (int)$status . "' WHERE `opayo_order_id` = '" . (int)$opayo_order_id . "'");
	}

	public function rebate($order_id, $amount) {
		$opayo_order = $this->getOrder($order_id);

		if (!empty($opayo_order) && $opayo_order['rebate_status'] != 1) {
			$refund_data = array();

			// Setting
			$_config = new Config();
			$_config->load('opayo');
			
			$config_setting = $_config->get('opayo_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_opayo_setting'));

			if ($setting['general']['environment'] == 'live') {
				$url = 'https://live.opayo.eu.elavon.com/gateway/service/refund.vsp';
				$refund_data['VPSProtocol'] = '4.00';
			} elseif ($setting['general']['environment'] == 'test') {
				$url = 'https://sandbox.opayo.eu.elavon.com/gateway/service/refund.vsp';
				$refund_data['VPSProtocol'] = '4.00';
			}

			$refund_data['TxType'] = 'REFUND';
			$refund_data['Vendor'] = $this->config->get('payment_opayo_vendor');
			$refund_data['VendorTxCode'] = $opayo_order['opayo_order_id'] . rand();
			$refund_data['Amount'] = $amount;
			$refund_data['Currency'] = $opayo_order['currency_code'];
			$refund_data['Description'] = substr($this->config->get('config_name'), 0, 100);
			$refund_data['RelatedVPSTxId'] = $opayo_order['VPSTxId'];
			$refund_data['RelatedVendorTxCode'] = $opayo_order['VendorTxCode'];
			$refund_data['RelatedSecurityKey'] = $opayo_order['SecurityKey'];
			$refund_data['RelatedTxAuthNo'] = $opayo_order['TxAuthNo'];

			$response_data = $this->sendCurl($url, $refund_data);

			return $response_data;
		} else {
			return false;
		}
	}

	public function updateRebateStatus($opayo_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "opayo_order` SET `rebate_status` = '" . (int)$status . "' WHERE `opayo_order_id` = '" . (int)$opayo_order_id . "'");
	}

	public function getOrder($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "opayo_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($query->num_rows) {
			$order = $query->row;
			$order['transactions'] = $this->getOrderTransactions($order['opayo_order_id']);

			return $order;
		} else {
			return false;
		}
	}

	public function getOrderTransactions($opayo_order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "opayo_order_transaction` WHERE `opayo_order_id` = '" . (int)$opayo_order_id . "'");

		if ($query->num_rows) {
			return $query->rows;
		} else {
			return false;
		}
	}

	public function addOrderTransaction($opayo_order_id, $type, $total) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "opayo_order_transaction` SET `opayo_order_id` = '" . (int)$opayo_order_id . "', `date_added` = now(), `type` = '" . $this->db->escape($type) . "', `amount` = '" . (float)$total . "'");
	}

	public function getTotalReleased($opayo_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "opayo_order_transaction` WHERE `opayo_order_id` = '" . (int)$opayo_order_id . "' AND (`type` = 'payment' OR `type` = 'rebate')");

		return (float)$query->row['total'];
	}

	public function getTotalRebated($opayo_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "opayo_order_transaction` WHERE `opayo_order_id` = '" . (int)$opayo_order_id . "' AND 'rebate'");

		return (float)$query->row['total'];
	}
	
	public function editRecurringStatus($order_recurring_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = '" . (int)$status . "' WHERE `order_recurring_id` = '" . (int)$order_recurring_id . "'");
	}

	public function sendCurl($url, $payment_data) {
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
			if (strpos($string, '=') === false) {
				continue;
			}
			
			$parts = explode('=', $string, 2);
			
			$data[trim($parts[0])] = trim($parts[1]);
		}
		
		return $data;
	}

	public function log($title, $data) {
		$_config = new Config();
		$_config->load('opayo');
		
		$config_setting = $_config->get('opayo_setting');
		
		$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_opayo_setting'));
		
		if ($setting['general']['debug']) {
			$log = new Log('opayo.log');
			
			$log->write($title . ': ' . print_r($data, true));
		}
	}
}
