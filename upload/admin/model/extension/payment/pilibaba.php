<?php
class ModelExtensionPaymentPilibaba extends Model {
	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "pilibaba_order` (
			`pilibaba_order_id` int(11) NOT NULL AUTO_INCREMENT,
			`order_id` int(11) NOT NULL DEFAULT '0',
			`amount` double NOT NULL,
			`fee` double NOT NULL,
			`tracking` VARCHAR(50) NOT NULL DEFAULT '',
			`date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (`pilibaba_order_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "pilibaba_order`");

		$this->disablePiliExpress();

		$this->log('Module uninstalled');
	}

	public function getCurrencies() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://www.pilibaba.com/pilipay/getCurrency');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		curl_close($ch);

		return json_decode($response, true);
	}

	public function getWarehouses() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://www.pilibaba.com/pilipay/getAddressList');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		curl_close($ch);

		return json_decode($response, true);
	}

	public function getOrder($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "pilibaba_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($query->num_rows) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function register($email, $password, $currency, $warehouse, $country, $environment) {
		$this->log('Posting register');

		if ($warehouse == 'other') {
			$warehouse = '';
		}

		if ($warehouse) {
			$country = '';
		}

		if ($environment == 'live') {
			$url = 'http://en.pilibaba.com/autoRegist';
		} else {
			$url = 'http://preen.pilibaba.com/autoRegist';
		}

		$this->log('URL: ' . $url);

		$app_secret = strtoupper(md5((($warehouse) ? $warehouse : $country) . '0210000574' . '0b8l3ww5' . $currency . $email . md5($password)));

		$data = array(
			'platformNo'  => '0210000574',
			'appSecret'   => $app_secret,
			'email'       => $email,
			'password'    => md5($password),
			'currency'    => $currency,
			'logistics'   => $warehouse,
			'countryCode' => $country
		);

		$this->log('Data: ' . print_r($data, true));

		$headers = array('Accept: application/json','Content-Type: application/json');

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		if (curl_errno($ch)) {
			$this->log('cURL error: ' . curl_errno($ch));
		}
		curl_close($ch);

		$this->log('Response: ' . print_r($response, true));

		return json_decode($response, true);
	}

	public function updateTrackingNumber($order_id, $tracking_number, $merchant_number) {
		$this->log('Posting tracking');

		$sign_msg = strtoupper(md5($order_id . $tracking_number . $merchant_number . $this->config->get('payment_pilibaba_secret_key')));

		if ($this->config->get('payment_pilibaba_environment') == 'live') {
			$url = 'https://www.pilibaba.com/pilipay/updateTrackNo';
		} else {
			$url = 'http://pre.pilibaba.com/pilipay/updateTrackNo';
		}

		$url .= '?orderNo=' . $order_id . '&logisticsNo=' . $tracking_number . '&merchantNo=' . $merchant_number . '&signMsg=' . $sign_msg;

		$this->log('URL: ' . $url);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		if (curl_errno($ch)) {
			$this->log('cURL error: ' . curl_errno($ch));
		}
		curl_close($ch);

		$this->db->query("UPDATE `" . DB_PREFIX . "pilibaba_order` SET `tracking` = '" . $this->db->escape($tracking_number) . "' WHERE `order_id` = '" . (int)$order_id . "'");
	}

	public function enablePiliExpress() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `type` = 'shipping' AND `code` = 'pilibaba'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = 'shipping', `code` = 'pilibaba'");
		}
	}

	public function disablePiliExpress() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `type` = 'shipping' AND `code` = 'pilibaba'");
	}

	public function log($data) {
		if ($this->config->has('payment_pilibaba_logging') && $this->config->get('payment_pilibaba_logging')) {
			$log = new Log('pilibaba.log');

			$log->write($data);
		}
	}
}