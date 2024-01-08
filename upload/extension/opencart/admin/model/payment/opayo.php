<?php
/**
 * Class Opayo
 *
 * @package Opencart\Admin\Model\Extension\Opencart\Payment
 */
namespace Opencart\Admin\Model\Extension\Opayo\Payment;
class Opayo extends \Opencart\System\Engine\Model {
	/**
	 * Install
	 *
	 * @return void
	 */
	public function install(): void {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "opayo_order` (
			  `opayo_order_id` int(11) NOT NULL AUTO_INCREMENT,
			  `order_id` int(11) NOT NULL,
			  `vps_tx_id` varchar(50),
			  `vendor_tx_code` varchar(50) NOT NULL,
			  `security_key` varchar(50) NOT NULL,
			  `tx_auth_no` varchar(50),
			  `date_added` datetime NOT NULL,
			  `date_modified` datetime NOT NULL,
			  `release_status` int(1) DEFAULT NULL,
			  `void_status` int(1) DEFAULT NULL,
			  `settle_type` int(1) DEFAULT NULL,
			  `rebate_status` int(1) DEFAULT NULL,
			  `currency_code` varchar(3) NOT NULL,
			  `total` decimal(15,4) NOT NULL,
			  `card_id` int(11),
			  PRIMARY KEY (`opayo_order_id`),
			  KEY (`order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "opayo_order_transaction` (
			  `opayo_order_transaction_id` int(11) NOT NULL AUTO_INCREMENT,
			  `opayo_order_id` int(11) NOT NULL,
			  `date_added` datetime NOT NULL,
			  `type` ENUM('auth', 'payment', 'rebate', 'void') DEFAULT NULL,
			  `amount` decimal(15,4) NOT NULL,
			  PRIMARY KEY (`opayo_order_transaction_id`),
			  KEY (`opayo_order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "opayo_order_subscription` (
			  `opayo_order_subscription_id` int(11) NOT NULL AUTO_INCREMENT,
			  `order_id` int(11) NOT NULL,
			  `subscription_id` int(11) NOT NULL,
			  `vps_tx_id` varchar(50),
			  `vendor_tx_code` varchar(50) NOT NULL,
			  `security_key` varchar(50) NOT NULL,
			  `tx_auth_no` varchar(50),
			  `date_added` datetime NOT NULL,
			  `date_modified` datetime NOT NULL,
			  `next_payment` datetime NOT NULL,
			  `trial_end` datetime DEFAULT NULL,
			  `subscription_end` datetime DEFAULT NULL,
			  `currency_code` varchar(3) NOT NULL,
			  `total` DECIMAL(15,4) NOT NULL,
			  PRIMARY KEY (`opayo_order_subscription_id`),
			  KEY (`order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "opayo_card` (
			  `card_id` int(11) NOT NULL AUTO_INCREMENT,
			  `customer_id` int(11) NOT NULL,
			  `token` varchar(50) NOT NULL,
			  `digits` varchar(4) NOT NULL,
			  `expiry` varchar(5) NOT NULL,
			  `type` varchar(50) NOT NULL,
			  PRIMARY KEY (`card_id`),
			  KEY (`customer_id`),
			  KEY (`token`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
	}

	/**
	 * Uninstall
	 *
	 * @return void
	 */
	public function uninstall(): void {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "opayo_order`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "opayo_order_transaction`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "opayo_order_subscription`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "opayo_card`;");
	}

	/**
	 * Void
	 *
	 * @param int $order_id
	 *
	 * @return array<string, array<string, string>|string>
	 */
	public function void(int $order_id): array {
		$opayo_order = $this->getOrder($order_id);

		if (!empty($opayo_order) && ($opayo_order['release_status'] == 0)) {
			$void_data = [];

			// Setting
			$_config = new \Opencart\System\Engine\Config();
			$_config->addPath(DIR_EXTENSION . 'opayo/system/config/');
			$_config->load('opayo');

			$config_setting = $_config->get('opayo_setting');

			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_opayo_setting'));

			if ($setting['general']['environment'] == 'live') {
				$url = 'https://live.sagepay.com/gateway/service/void.vsp';
				$void_data['VPSProtocol'] = '4.00';
			} elseif ($setting['general']['environment'] == 'test') {
				$url = 'https://test.sagepay.com/gateway/service/void.vsp';
				$void_data['VPSProtocol'] = '4.00';
			}

			$void_data['TxType'] = 'VOID';
			$void_data['Vendor'] = $this->config->get('payment_opayo_vendor');
			$void_data['VendorTxCode'] = $opayo_order['vendor_tx_code'];
			$void_data['VPSTxId'] = $opayo_order['vps_tx_id'];
			$void_data['SecurityKey'] = $opayo_order['security_key'];
			$void_data['TxAuthNo'] = $opayo_order['tx_auth_no'];

			return $this->sendCurl($url, $void_data);
		} else {
			return [];
		}
	}

	/**
	 * Update Void Status
	 *
	 * @param int $opayo_order_id
	 * @param int $status
	 *
	 * @return void
	 */
	public function updateVoidStatus(int $opayo_order_id, int $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "opayo_order` SET `void_status` = '" . (int)$status . "' WHERE `opayo_order_id` = '" . (int)$opayo_order_id . "'");
	}

	/**
	 * Release
	 *
	 * @param int   $order_id
	 * @param float $amount
	 *
	 * @return array<string, array<string, string>|string>
	 */
	public function release(int $order_id, float $amount): array {
		$opayo_order = $this->getOrder($order_id);

		$total_released = $this->getTotalReleased($opayo_order['opayo_order_id']);

		if (!empty($opayo_order) && ($opayo_order['release_status'] == 0) && ($total_released + $amount <= $opayo_order['total'])) {
			$release_data = [];

			// Setting
			$_config = new \Opencart\System\Engine\Config();
			$_config->addPath(DIR_EXTENSION . 'opayo/system/config/');
			$_config->load('opayo');

			$config_setting = $_config->get('opayo_setting');

			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_opayo_setting'));

			if ($setting['general']['environment'] == 'live') {
				$url = 'https://live.sagepay.com/gateway/service/release.vsp';
				$release_data['VPSProtocol'] = '4.00';
			} elseif ($setting['general']['environment'] == 'test') {
				$url = 'https://test.sagepay.com/gateway/service/release.vsp';
				$release_data['VPSProtocol'] = '4.00';
			}

			$release_data['TxType'] = 'RELEASE';
			$release_data['Vendor'] = $this->config->get('payment_opayo_vendor');
			$release_data['VendorTxCode'] = $opayo_order['vendor_tx_code'];
			$release_data['VPSTxId'] = $opayo_order['vps_tx_id'];
			$release_data['SecurityKey'] = $opayo_order['security_key'];
			$release_data['TxAuthNo'] = $opayo_order['tx_auth_no'];
			$release_data['Amount'] = $amount;

			return $this->sendCurl($url, $release_data);
		} else {
			return [];
		}
	}

	/**
	 * Update Release Status
	 *
	 * @param int $opayo_order_id
	 * @param int $status
	 *
	 * @return void
	 */
	public function updateReleaseStatus(int $opayo_order_id, int $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "opayo_order` SET `release_status` = '" . (int)$status . "' WHERE `opayo_order_id` = '" . (int)$opayo_order_id . "'");
	}

	/**
	 * Rebate
	 *
	 * @param int   $order_id
	 * @param float $amount
	 *
	 * @return array<string, array<string, string>|string>
	 */
	public function rebate(int $order_id, float $amount): array {
		$opayo_order = $this->getOrder($order_id);

		if (!empty($opayo_order) && ($opayo_order['rebate_status'] != 1)) {
			$refund_data = [];

			// Setting
			$_config = new \Opencart\System\Engine\Config();
			$_config->addPath(DIR_EXTENSION . 'opayo/system/config/');
			$_config->load('opayo');

			$config_setting = $_config->get('opayo_setting');

			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_opayo_setting'));

			if ($setting['general']['environment'] == 'live') {
				$url = 'https://live.sagepay.com/gateway/service/refund.vsp';
				$refund_data['VPSProtocol'] = '4.00';
			} elseif ($setting['general']['environment'] == 'test') {
				$url = 'https://test.sagepay.com/gateway/service/refund.vsp';
				$refund_data['VPSProtocol'] = '4.00';
			}

			$refund_data['TxType'] = 'REFUND';
			$refund_data['Vendor'] = $this->config->get('payment_opayo_vendor');
			$refund_data['VendorTxCode'] = $opayo_order['opayo_order_id'] . mt_rand();
			$refund_data['Amount'] = $amount;
			$refund_data['Currency'] = $opayo_order['currency_code'];
			$refund_data['Description'] = substr($this->config->get('config_name'), 0, 100);
			$refund_data['RelatedVPSTxId'] = $opayo_order['vps_tx_id'];
			$refund_data['RelatedVendorTxCode'] = $opayo_order['vendor_tx_code'];
			$refund_data['RelatedSecurityKey'] = $opayo_order['security_key'];
			$refund_data['RelatedTxAuthNo'] = $opayo_order['tx_auth_no'];

			return $this->sendCurl($url, $refund_data);
		} else {
			return [];
		}
	}

	/**
	 * Update Rebate Status
	 *
	 * @param int $opayo_order_id
	 * @param int $status
	 *
	 * @return void
	 */
	public function updateRebateStatus(int $opayo_order_id, int $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "opayo_order` SET `rebate_status` = '" . (int)$status . "' WHERE `opayo_order_id` = '" . (int)$opayo_order_id . "'");
	}

	/**
	 * getOrder
	 *
	 * @param int $order_id
	 *
	 * @return array<string, mixed>
	 */
	public function getOrder(int $order_id): array {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "opayo_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($qry->num_rows) {
			$order = $qry->row;
			$order['transactions'] = $this->getOrderTransactions($order['opayo_order_id']);

			return $order;
		} else {
			return [];
		}
	}

	/**
	 * Get Order Transactions
	 *
	 * @param int $opayo_order_id
	 *
	 * @return array<int, array<string, mixed>>
	 */
	private function getOrderTransactions(int $opayo_order_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "opayo_order_transaction` WHERE `opayo_order_id` = '" . (int)$opayo_order_id . "'");

		if ($query->num_rows) {
			return $query->rows;
		} else {
			return [];
		}
	}

	/**
	 * Add Order Transaction
	 *
	 * @param int    $opayo_order_id
	 * @param string $type
	 * @param float  $total
	 *
	 * @return void
	 */
	public function addOrderTransaction(int $opayo_order_id, string $type, float $total): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "opayo_order_transaction` SET `opayo_order_id` = '" . (int)$opayo_order_id . "', `date_added` = now(), `type` = '" . $this->db->escape($type) . "', `amount` = '" . (float)$total . "'");
	}

	/**
	 * Get Total Released
	 *
	 * @param int $opayo_order_id
	 *
	 * @return float
	 */
	public function getTotalReleased(int $opayo_order_id): float {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "opayo_order_transaction` WHERE `opayo_order_id` = '" . (int)$opayo_order_id . "' AND (`type` = 'payment' OR `type` = 'rebate')");

		return (float)$query->row['total'];
	}

	/**
	 * Get Total Rebated
	 *
	 * @param int $opayo_order_id
	 *
	 * @return float
	 */
	public function getTotalRebated(int $opayo_order_id): float {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "opayo_order_transaction` WHERE `opayo_order_id` = '" . (int)$opayo_order_id . "' AND 'rebate'");

		return (float)$query->row['total'];
	}

	/**
	 * Send Curl
	 *
	 * @param string       $url
	 * @param array<mixed> $payment_data
	 *
	 * @return array<string, array<string, string>|string>
	 */
	public function sendCurl(string $url, array $payment_data): array {
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

		foreach ($response_info as $i => $string) {
			if (strpos($string, '=')) {
				$parts = explode('=', $string, 2);
				$data['RepeatResponseData_' . $i][trim($parts[0])] = trim($parts[1]);
			} elseif (strpos($string, '=')) {
				$parts = explode('=', $string, 2);
				$data[trim($parts[0])] = trim($parts[1]);
			}
		}

		return $data;
	}

	/**
	 * Log
	 *
	 * @param string  $title
	 * @param ?string $data
	 *
	 * @return void
	 */
	public function log(string $title, ?string $data): void {
		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'opayo/system/config/');
		$_config->load('opayo');

		$config_setting = $_config->get('opayo_setting');

		$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_opayo_setting'));

		if ($setting['general']['debug']) {
			$log = new \Opencart\System\Library\Log('opayo.log');

			$log->write($title . ': ' . print_r($data, 1));
		}
	}
}
