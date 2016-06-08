<?php
class ModelExtensionPaymentPilibaba extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/pilibaba');

		$status = true;

		if (!isset($this->session->data['shipping_method']['code']) || $this->session->data['shipping_method']['code'] != 'pilibaba.pilibaba') {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'			=> 'pilibaba',
				'title'			=> $this->language->get('text_title'),
				'terms'			=> '',
				'sort_order'	=> $this->config->get('pilibaba_sort_order')
			);
		}

		return $method_data;
	}

	public function getOrderTaxAmount($order_id) {
		$query = $this->db->query("SELECT SUM(`value`) AS `value` FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = '" . (int)$order_id . "' AND `code` = 'tax'");

		if ($query->num_rows) {
			return intval(round($query->row['value'], 2) * 100);
		} else {
			return 0;
		}
	}

	public function addPilibabaOrder($response_data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "pilibaba_order` SET `order_id` = '" . (int)$response_data['orderNo'] . "', `amount` = '" . (float)$response_data['orderAmount'] . "', `fee` = '" . (float)$response_data['fee'] . "', `tracking` = '', `date_added` = NOW()");
	}

	public function getConsumerInfo($order_id) {
		$sign_msg = strtoupper(md5($this->config->get('pilibaba_merchant_number') . $order_id . 'MD5' . $this->config->get('pilibaba_secret_key')));

		if ($this->config->get('pilibaba_environment') == 'live') {
			$url = 'https://www.pilibaba.com/pilipay/consumerInfo';
		} else {
			$url = 'http://pre.pilibaba.com/pilipay/consumerInfo';
		}

		$url .= '?merchantNo=' . $this->config->get('pilibaba_merchant_number') . '&orderNo=' . $order_id . '&signType=' . 'MD5' . '&signMsg=' . $sign_msg;

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

		$this->log('Response: ' . print_r($response, true));

		return json_decode($response, true);
	}

	public function updateOrderInfo($data, $order_id) {
		$parts = explode(' ', $data['name']);

		$data['lastname'] = array_pop($parts);

		$data['firstname'] = implode(' ', $parts);

		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `firstname` = '" . $this->db->escape($data['firstname']) . "', `lastname` = '" . $this->db->escape($data['lastname']) . "', `email` = '" . $this->db->escape($data['email']) . "', `telephone` = '" . $this->db->escape($data['mobile']) . "', `payment_firstname` = '" . $this->db->escape($data['firstname']) . "', `payment_lastname` = '" . $this->db->escape($data['lastname']) . "', `payment_address_1` = '" . $this->db->escape($data['address']) . "', `payment_city` = '" . $this->db->escape($data['city']) . "', `payment_postcode` = '" . $this->db->escape($data['zipcode']) . "', `payment_country` = '" . $this->db->escape($data['country']) . "', `payment_zone` = '" . $this->db->escape($data['district']) . "', `shipping_firstname` = '" . $this->db->escape($data['firstname']) . "', `shipping_lastname` = '" . $this->db->escape($data['lastname']) . "', `shipping_address_1` = '" . $this->db->escape($data['address']) . "', `shipping_city` = '" . $this->db->escape($data['city']) . "', `shipping_postcode` = '" . $this->db->escape($data['zipcode']) . "', `shipping_country` = '" . $this->db->escape($data['country']) . "', `shipping_zone` = '" . $this->db->escape($data['district']) . "', `date_modified` = NOW() WHERE `order_id` = '" . (int)$order_id . "'");
	}

	public function log($data) {
		if ($this->config->get('pilibaba_logging')) {
			$log = new Log('pilibaba.log');

			$log->write($data);
		}
	}
}