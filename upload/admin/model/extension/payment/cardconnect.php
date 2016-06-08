<?php
class ModelExtensionPaymentCardConnect extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cardconnect_card` (
			  `cardconnect_card_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `cardconnect_order_id` INT(11) NOT NULL DEFAULT '0',
			  `customer_id` INT(11) NOT NULL DEFAULT '0',
			  `profileid` VARCHAR(16) NOT NULL DEFAULT '',
			  `token` VARCHAR(19) NOT NULL DEFAULT '',
			  `type` VARCHAR(50) NOT NULL DEFAULT '',
			  `account` VARCHAR(4) NOT NULL DEFAULT '',
			  `expiry` VARCHAR(4) NOT NULL DEFAULT '',
			  `date_added` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			  PRIMARY KEY (`cardconnect_card_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cardconnect_order` (
			  `cardconnect_order_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `order_id` INT(11) NOT NULL DEFAULT '0',
			  `customer_id` INT(11) NOT NULL DEFAULT '0',
			  `payment_method` VARCHAR(255) NOT NULL DEFAULT '',
			  `retref` VARCHAR(12) NOT NULL DEFAULT '',
			  `authcode` VARCHAR(6) NOT NULL DEFAULT '',
			  `currency_code` VARCHAR(3) NOT NULL DEFAULT '',
			  `total` DECIMAL(10, 2) NOT NULL DEFAULT '0.00',
			  `date_added` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			  PRIMARY KEY (`cardconnect_order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cardconnect_order_transaction` (
			  `cardconnect_order_transaction_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `cardconnect_order_id` INT(11) NOT NULL DEFAULT '0',
			  `type` VARCHAR(50) NOT NULL DEFAULT '',
			  `retref` VARCHAR(12) NOT NULL DEFAULT '',
			  `amount` DECIMAL(10, 2) NOT NULL DEFAULT '0.00',
			  `status` VARCHAR(255) NOT NULL DEFAULT '',
			  `date_modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			  `date_added` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
			  PRIMARY KEY (`cardconnect_order_transaction_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "cardconnect_card`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "cardconnect_order`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "cardconnect_order_transaction`");

		$this->log('Module uninstalled');
	}

	public function getOrder($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "cardconnect_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($query->num_rows) {
			$order = $query->row;

			$order['transactions'] = $this->getTransactions($order['cardconnect_order_id']);

			return $order;
		} else {
			return false;
		}
	}

	private function getTransactions($cardconnect_order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "cardconnect_order_transaction` WHERE `cardconnect_order_id` = '" . (int)$cardconnect_order_id . "'");

		if ($query->num_rows) {
			return $query->rows;
		} else {
			return array();
		}
	}

	public function getTotalCaptured($cardconnect_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "cardconnect_order_transaction` WHERE `cardconnect_order_id` = '" . (int)$cardconnect_order_id . "' AND (`type` = 'payment' OR `type` = 'refund')");

		return (float)$query->row['total'];
	}

	public function inquire($order_info, $retref) {
		$this->log('Posting inquire to CardConnect');

		$this->log('Order ID: ' . $order_info['order_id']);

		$url = 'https://' . $this->config->get('cardconnect_site') . '.cardconnect.com:' . (($this->config->get('cardconnect_environment') == 'live') ? 8443 : 6443) . '/cardconnect/rest/inquire/' . $retref . '/' . $this->config->get('cardconnect_merchant_id');

		$header = array();

		$header[] = 'Content-type: application/json';
		$header[] = 'Authorization: Basic ' . base64_encode($this->config->get('cardconnect_api_username') . ':' . $this->config->get('cardconnect_api_password'));

		$this->model_extension_payment_cardconnect->log('Header: ' . print_r($header, true));

		$this->model_extension_payment_cardconnect->log('URL: ' . $url);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response_data = curl_exec($ch);
		if (curl_errno($ch)) {
			$this->model_extension_payment_cardconnect->log('cURL error: ' . curl_errno($ch));
		}
		curl_close($ch);

		$response_data = json_decode($response_data, true);

		$this->log('Response: ' . print_r($response_data, true));

		return $response_data;
	}

	public function capture($order_info, $amount) {
		$this->load->model('sale/order');

		$this->log('Posting capture to CardConnect');

		$this->log('Order ID: ' . $order_info['order_id']);

		$order = $this->model_sale_order->getOrder($order_info['order_id']);

		$totals = $this->model_sale_order->getOrderTotals($order_info['order_id']);

		$shipping_cost = '';

		foreach($totals as $total) {
			if ($total['code'] == 'shipping') {
				$shipping_cost = $total['value'];
			}
		}

		$products = $this->model_sale_order->getOrderProducts($order_info['order_id']);

		$items = array();

		$i = 1;

		foreach ($products as $product) {
			$items[] = array(
				'lineno'      => $i,
				'material'    => '',
				'description' => $product['name'],
				'upc'         => '',
				'quantity'    => $product['quantity'],
				'uom'         => '',
				'unitcost'    => $product['price'],
				'netamnt'     => $product['total'],
				'taxamnt'     => $product['tax'],
				'discamnt'    => ''
			);

			$i++;
		}

		$data = array(
			'merchid'       => $this->config->get('cardconnect_merchant_id'),
			'retref'        => $order_info['retref'],
			'authcode'      => $order_info['authcode'],
			'ponumber'      => $order_info['order_id'],
			'amount'        => round(floatval($amount), 2, PHP_ROUND_HALF_DOWN),
			'currency'      => $order_info['currency_code'],
			'frtamnt'       => $shipping_cost,
			'dutyamnt'      => '',
			'orderdate'     => '',
			'shiptozip'     => $order['shipping_postcode'],
			'shipfromzip'   => '',
			'shiptocountry' => $order['shipping_iso_code_2'],
			'Items'         => $items
		);

		$data_json = json_encode($data);

		$url = 'https://' . $this->config->get('cardconnect_site') . '.cardconnect.com:' . (($this->config->get('cardconnect_environment') == 'live') ? 8443 : 6443) . '/cardconnect/rest/capture';

		$header = array();

		$header[] = 'Content-type: application/json';
		$header[] = 'Content-length: ' . strlen($data_json);
		$header[] = 'Authorization: Basic ' . base64_encode($this->config->get('cardconnect_api_username') . ':' . $this->config->get('cardconnect_api_password'));

		$this->model_extension_payment_cardconnect->log('Header: ' . print_r($header, true));

		$this->model_extension_payment_cardconnect->log('Post Data: ' . print_r($data, true));

		$this->model_extension_payment_cardconnect->log('URL: ' . $url);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response_data = curl_exec($ch);
		if (curl_errno($ch)) {
			$this->model_extension_payment_cardconnect->log('cURL error: ' . curl_errno($ch));
		}
		curl_close($ch);

		$response_data = json_decode($response_data, true);

		$this->log('Response: ' . print_r($response_data, true));

		return $response_data;
	}

	public function refund($order_info, $amount) {
		$this->log('Posting refund to CardConnect');

		$this->log('Order ID: ' . $order_info['order_id']);

		$data = array(
			'merchid'   => $this->config->get('cardconnect_merchant_id'),
			'amount'    => round(floatval($amount), 2, PHP_ROUND_HALF_DOWN),
			'currency'  => $order_info['currency_code'],
			'retref'    => $order_info['retref']
		);

		$data_json = json_encode($data);

		$url = 'https://' . $this->config->get('cardconnect_site') . '.cardconnect.com:' . (($this->config->get('cardconnect_environment') == 'live') ? 8443 : 6443) . '/cardconnect/rest/refund';

		$header = array();

		$header[] = 'Content-type: application/json';
		$header[] = 'Content-length: ' . strlen($data_json);
		$header[] = 'Authorization: Basic ' . base64_encode($this->config->get('cardconnect_api_username') . ':' . $this->config->get('cardconnect_api_password'));

		$this->model_extension_payment_cardconnect->log('Header: ' . print_r($header, true));

		$this->model_extension_payment_cardconnect->log('Post Data: ' . print_r($data, true));

		$this->model_extension_payment_cardconnect->log('URL: ' . $url);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response_data = curl_exec($ch);
		if (curl_errno($ch)) {
			$this->model_extension_payment_cardconnect->log('cURL error: ' . curl_errno($ch));
		}
		curl_close($ch);

		$response_data = json_decode($response_data, true);

		$this->log('Response: ' . print_r($response_data, true));

		return $response_data;
	}

	public function void($order_info, $retref) {
		$this->log('Posting void to CardConnect');

		$this->log('Order ID: ' . $order_info['order_id']);

		$data = array(
			'merchid'   => $this->config->get('cardconnect_merchant_id'),
			'amount'    => 0,
			'currency'  => $order_info['currency_code'],
			'retref'    => $retref
		);

		$data_json = json_encode($data);

		$url = 'https://' . $this->config->get('cardconnect_site') . '.cardconnect.com:' . (($this->config->get('cardconnect_environment') == 'live') ? 8443 : 6443) . '/cardconnect/rest/void';

		$header = array();

		$header[] = 'Content-type: application/json';
		$header[] = 'Content-length: ' . strlen($data_json);
		$header[] = 'Authorization: Basic ' . base64_encode($this->config->get('cardconnect_api_username') . ':' . $this->config->get('cardconnect_api_password'));

		$this->model_extension_payment_cardconnect->log('Header: ' . print_r($header, true));

		$this->model_extension_payment_cardconnect->log('Post Data: ' . print_r($data, true));

		$this->model_extension_payment_cardconnect->log('URL: ' . $url);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response_data = curl_exec($ch);
		if (curl_errno($ch)) {
			$this->model_extension_payment_cardconnect->log('cURL error: ' . curl_errno($ch));
		}
		curl_close($ch);

		$response_data = json_decode($response_data, true);

		$this->log('Response: ' . print_r($response_data, true));

		return $response_data;
	}

	public function updateTransactionStatusByRetref($retref, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "cardconnect_order_transaction` SET `status` = '" . $this->db->escape($status) . "', `date_modified` = NOW() WHERE `retref` = '" . $this->db->escape($retref) . "'");
	}

	public function addTransaction($cardconnect_order_id, $type, $retref, $amount, $status) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "cardconnect_order_transaction` SET `cardconnect_order_id` = '" . (int)$cardconnect_order_id . "', `type` = '" . $this->db->escape($type) . "', `retref` = '" . $this->db->escape($retref) . "', `amount` = '" . (float)$amount . "', `status` = '" . $this->db->escape($status) . "', `date_modified` = NOW(), `date_added` = NOW()");
	}

	public function log($data) {
		if ($this->config->get('cardconnect_logging')) {
			$log = new Log('cardconnect.log');

			$log->write($data);
		}
	}
}