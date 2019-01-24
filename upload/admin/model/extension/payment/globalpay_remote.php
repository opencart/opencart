<?php
class ModelExtensionPaymentGlobalpayRemote extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "globalpay_remote_order` (
			  `globalpay_remote_order_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `order_id` INT(11) NOT NULL,
			  `order_ref` CHAR(50) NOT NULL,
			  `order_ref_previous` CHAR(50) NOT NULL,
			  `pasref` VARCHAR(50) NOT NULL,
			  `pasref_previous` VARCHAR(50) NOT NULL,
			  `date_added` DATETIME NOT NULL,
			  `date_modified` DATETIME NOT NULL,
			  `capture_status` INT(1) DEFAULT NULL,
			  `void_status` INT(1) DEFAULT NULL,
			  `settle_type` INT(1) DEFAULT NULL,
			  `rebate_status` INT(1) DEFAULT NULL,
			  `currency_code` CHAR(3) NOT NULL,
			  `authcode` VARCHAR(30) NOT NULL,
			  `account` VARCHAR(30) NOT NULL,
			  `total` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`globalpay_remote_order_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "globalpay_remote_order_transaction` (
			  `globalpay_remote_order_transaction_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `globalpay_remote_order_id` INT(11) NOT NULL,
			  `date_added` DATETIME NOT NULL,
			  `type` ENUM('auth', 'payment', 'rebate', 'void') DEFAULT NULL,
			  `amount` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`globalpay_remote_order_transaction_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
	}

	public function void($order_id) {
		$globalpay_order = $this->getOrder($order_id);

		if (!empty($globalpay_order)) {
			$timestamp = strftime("%Y%m%d%H%M%S");
			$merchant_id = $this->config->get('payment_globalpay_remote_merchant_id');
			$secret = $this->config->get('payment_globalpay_remote_secret');

			$this->logger('Void hash construct: ' . $timestamp . '.' . $merchant_id . '.' . $globalpay_order['order_ref'] . '...');

			$tmp = $timestamp . '.' . $merchant_id . '.' . $globalpay_order['order_ref'] . '...';
			$hash = sha1($tmp);
			$tmp = $hash . '.' . $secret;
			$hash = sha1($tmp);

			$xml = '';
			$xml .= '<request type="void" timestamp="' . $timestamp . '">';
			$xml .= '<merchantid>' . $merchant_id . '</merchantid>';
			$xml .= '<account>' . $globalpay_order['account'] . '</account>';
			$xml .= '<orderid>' . $globalpay_order['order_ref'] . '</orderid>';
			$xml .= '<pasref>' . $globalpay_order['pasref'] . '</pasref>';
			$xml .= '<authcode>' . $globalpay_order['authcode'] . '</authcode>';
			$xml .= '<sha1hash>' . $hash . '</sha1hash>';
			$xml .= '</request>';

			$this->logger('Void XML request:\r\n' . print_r(simplexml_load_string($xml), 1));

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://epage.payandshop.com/epage-remote.cgi");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, "OpenCart " . VERSION);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			$response = curl_exec ($ch);
			curl_close ($ch);

			return simplexml_load_string($response);
		} else {
			return false;
		}
	}

	public function updateVoidStatus($globalpay_remote_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "globalpay_remote_order` SET `void_status` = '" . (int)$status . "' WHERE `globalpay_remote_order_id` = '" . (int)$globalpay_remote_order_id . "'");
	}

	public function capture($order_id, $amount) {
		$globalpay_order = $this->getOrder($order_id);

		if (!empty($globalpay_order) && $globalpay_order['capture_status'] == 0) {
			$timestamp = strftime("%Y%m%d%H%M%S");
			$merchant_id = $this->config->get('payment_globalpay_remote_merchant_id');
			$secret = $this->config->get('payment_globalpay_remote_secret');

			if ($globalpay_order['settle_type'] == 2) {
				$this->logger('Capture hash construct: ' . $timestamp . '.' . $merchant_id . '.' . $globalpay_order['order_ref'] . '.' . (int)round($amount*100) . '.' . (string)$globalpay_order['currency_code'] . '.');

				$tmp = $timestamp . '.' . $merchant_id . '.' . $globalpay_order['order_ref'] . '.' . (int)round($amount*100) . '.' . (string)$globalpay_order['currency_code'] . '.';
				$hash = sha1($tmp);
				$tmp = $hash . '.' . $secret;
				$hash = sha1($tmp);

				$settle_type = 'multisettle';
				$xml_amount = '<amount currency="' . (string)$globalpay_order['currency_code'] . '">' . (int)round($amount*100) . '</amount>';
			} else {
				//$this->logger('Capture hash construct: ' . $timestamp . '.' . $merchant_id . '.' . $globalpay_order['order_ref'] . '...');
				$this->logger('Capture hash construct: ' . $timestamp . '.' . $merchant_id . '.' . $globalpay_order['order_ref'] . '.' . (int)round($amount*100) . '.' . (string)$globalpay_order['currency_code'] . '.');

				$tmp = $timestamp . '.' . $merchant_id . '.' . $globalpay_order['order_ref'] . '.' . (int)round($amount*100) . '.' . (string)$globalpay_order['currency_code'] . '.';
				$hash = sha1($tmp);
				$tmp = $hash . '.' . $secret;
				$hash = sha1($tmp);

				$settle_type = 'settle';
				$xml_amount = '<amount currency="' . (string)$globalpay_order['currency_code'] . '">' . (int)round($amount*100) . '</amount>';
			}

			$xml = '';
			$xml .= '<request type="' . $settle_type . '" timestamp="' . $timestamp . '">';
			$xml .= '<merchantid>' . $merchant_id . '</merchantid>';
			$xml .= '<account>' . $globalpay_order['account'] . '</account>';
			$xml .= '<orderid>' . $globalpay_order['order_ref'] . '</orderid>';
			$xml .= $xml_amount;
			$xml .= '<pasref>' . $globalpay_order['pasref'] . '</pasref>';
			$xml .= '<authcode>' . $globalpay_order['authcode'] . '</authcode>';
			$xml .= '<sha1hash>' . $hash . '</sha1hash>';
			$xml .= '</request>';

			$this->logger('Settle XML request:\r\n' . print_r(simplexml_load_string($xml), 1));

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://epage.payandshop.com/epage-remote.cgi");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, "OpenCart " . VERSION);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			$response = curl_exec ($ch);
			curl_close ($ch);

			return simplexml_load_string($response);
		} else {
			return false;
		}
	}

	public function updateCaptureStatus($globalpay_remote_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "globalpay_remote_order` SET `capture_status` = '" . (int)$status . "' WHERE `globalpay_remote_order_id` = '" . (int)$globalpay_remote_order_id . "'");
	}

	public function updateForRebate($globalpay_remote_order_id, $pas_ref, $order_ref) {
		$this->db->query("UPDATE `" . DB_PREFIX . "globalpay_remote_order` SET `order_ref_previous` = '_multisettle_" . $this->db->escape($order_ref) . "', `pasref_previous` = '" . $this->db->escape($pas_ref) . "' WHERE `globalpay_remote_order_id` = '" . (int)$globalpay_remote_order_id . "' LIMIT 1");
	}

	public function rebate($order_id, $amount) {
		$globalpay_order = $this->getOrder($order_id);

		if (!empty($globalpay_order) && $globalpay_order['rebate_status'] != 1) {
			$timestamp = strftime("%Y%m%d%H%M%S");
			$merchant_id = $this->config->get('payment_globalpay_remote_merchant_id');
			$secret = $this->config->get('payment_globalpay_remote_secret');

			if ($globalpay_order['settle_type'] == 2) {
				$order_ref = '_multisettle_' . $globalpay_order['order_ref'];

				if (empty($globalpay_order['pasref_previous'])) {
					$pas_ref = $globalpay_order['pasref'];
				} else {
					$pas_ref = $globalpay_order['pasref_previous'];
				}
			} else {
				$order_ref = $globalpay_order['order_ref'];
				$pas_ref = $globalpay_order['pasref'];
			}

			$this->logger('Rebate hash construct: ' . $timestamp . '.' . $merchant_id . '.' . $order_ref . '.' . (int)round($amount*100) . '.' . $globalpay_order['currency_code'] . '.');

			$tmp = $timestamp . '.' . $merchant_id . '.' . $order_ref . '.' . (int)round($amount*100) . '.' . $globalpay_order['currency_code'] . '.';
			$hash = sha1($tmp);
			$tmp = $hash . '.' . $secret;
			$hash = sha1($tmp);

			$rebatehash = sha1($this->config->get('payment_globalpay_remote_rebate_password'));

			$xml = '';
			$xml .= '<request type="rebate" timestamp="' . $timestamp . '">';
			$xml .= '<merchantid>' . $merchant_id . '</merchantid>';
			$xml .= '<account>' . $globalpay_order['account'] . '</account>';
			$xml .= '<orderid>' . $order_ref . '</orderid>';
			$xml .= '<pasref>' . $pas_ref . '</pasref>';
			$xml .= '<authcode>' . $globalpay_order['authcode'] . '</authcode>';
			$xml .= '<amount currency="' . (string)$globalpay_order['currency_code'] . '">' . (int)round($amount*100) . '</amount>';
			$xml .= '<refundhash>' . $rebatehash . '</refundhash>';
			$xml .= '<sha1hash>' . $hash . '</sha1hash>';
			$xml .= '</request>';

			$this->logger('Rebate XML request:\r\n' . print_r(simplexml_load_string($xml), 1));

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://epage.payandshop.com/epage-remote.cgi");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, "OpenCart " . VERSION);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			$response = curl_exec ($ch);
			curl_close ($ch);

			return simplexml_load_string($response);
		} else {
			return false;
		}
	}

	public function updateRebateStatus($globalpay_remote_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "globalpay_remote_order` SET `rebate_status` = '" . (int)$status . "' WHERE `globalpay_remote_order_id` = '" . (int)$globalpay_remote_order_id . "'");
	}

	public function getOrder($order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "globalpay_remote_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($qry->num_rows) {
			$order = $qry->row;
			$order['transactions'] = $this->getTransactions($order['globalpay_remote_order_id']);

			return $order;
		} else {
			return false;
		}
	}

	private function getTransactions($globalpay_remote_order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "globalpay_remote_order_transaction` WHERE `globalpay_remote_order_id` = '" . (int)$globalpay_remote_order_id . "'");

		if ($qry->num_rows) {
			return $qry->rows;
		} else {
			return false;
		}
	}

	public function addTransaction($globalpay_remote_order_id, $type, $total) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "globalpay_remote_order_transaction` SET `globalpay_remote_order_id` = '" . (int)$globalpay_remote_order_id . "', `date_added` = now(), `type` = '" . $this->db->escape($type) . "', `amount` = '" . (float)$total . "'");
	}

	public function logger($message) {
		if ($this->config->get('payment_globalpay_remote_debug') == 1) {
			$log = new Log('globalpay_remote.log');
			$log->write($message);
		}
	}

	public function getTotalCaptured($globalpay_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "globalpay_remote_order_transaction` WHERE `globalpay_remote_order_id` = '" . (int)$globalpay_order_id . "' AND (`type` = 'payment' OR `type` = 'rebate')");

		return (float)$query->row['total'];
	}

	public function getTotalRebated($globalpay_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "globalpay_remote_order_transaction` WHERE `globalpay_remote_order_id` = '" . (int)$globalpay_order_id . "' AND 'rebate'");

		return (double)$query->row['total'];
	}
}