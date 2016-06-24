<?php

class ModelExtensionPaymentBluePayRedirect extends Model {

	public function getMethod($address, $total) {
		$this->load->language('extension/payment/bluepay_redirect');

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE geo_zone_id = '" . (int)$this->config->get('bluepay_redirect_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('bluepay_redirect_total') > 0 && $this->config->get('bluepay_redirect_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('bluepay_redirect_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code' => 'bluepay_redirect',
				'title' => $this->language->get('text_title'),
				'terms' => '',
				'sort_order' => $this->config->get('bluepay_redirect_sort_order')
			);
		}

		return $method_data;
	}

	public function getCards($customer_id) {

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "bluepay_redirect_card` WHERE customer_id = '" . (int)$customer_id . "'");

		$card_data = array();

		$this->load->model('account/address');

		foreach ($query->rows as $row) {

			$card_data[] = array(
				'card_id' => $row['card_id'],
				'customer_id' => $row['customer_id'],
				'token' => $row['token'],
				'digits' => '**** ' . $row['digits'],
				'expiry' => $row['expiry'],
				'type' => $row['type'],
			);
		}
		return $card_data;
	}

	public function addCard($card_data) {
		$this->db->query("INSERT into `" . DB_PREFIX . "bluepay_redirect_card` SET customer_id = '" . $this->db->escape($card_data['customer_id']) . "', token = '" . $this->db->escape($card_data['Token']) . "', digits = '" . $this->db->escape($card_data['Last4Digits']) . "', expiry = '" . $this->db->escape($card_data['ExpiryDate']) . "', type = '" . $this->db->escape($card_data['CardType']) . "'");
	}

	public function addOrder($order_info, $response_data) {
		if ($this->config->get('bluepay_redirect_transaction') == 'SALE') {
			$release_status = 1;
		} else {
			$release_status = null;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "bluepay_redirect_order` SET `order_id` = '" . (int)$order_info['order_id'] . "', `transaction_id` = '" . $this->db->escape($response_data['RRNO']) . "', `date_added` = now(), `date_modified` = now(), `release_status` = '" . (int)$release_status . "',  `currency_code` = '" . $this->db->escape($order_info['currency_code']) . "', `total` = '" . $this->currency->format($order_info['total'], $order_info['currency_code'], false, false) . "'");

		return $this->db->getLastId();
	}

	public function getOrder($order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "bluepay_redirect_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($qry->num_rows) {
			$order = $qry->row;
			$order['transactions'] = $this->getTransactions($order['bluepay_redirect_order_id']);

			return $order;
		} else {
			return false;
		}
	}

	public function addTransaction($bluepay_redirect_order_id, $type, $order_info) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "bluepay_redirect_order_transaction` SET `bluepay_redirect_order_id` = '" . (int)$bluepay_redirect_order_id . "', `date_added` = now(), `type` = '" . $this->db->escape($type) . "', `amount` = '" . $this->currency->format($order_info['total'], $order_info['currency_code'], false, false) . "'");
	}

	private function getTransactions($bluepay_redirect_order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "bluepay_redirect_order_transaction` WHERE `bluepay_redirect_order_id` = '" . (int)$bluepay_redirect_order_id . "'");

		if ($qry->num_rows) {
			return $qry->rows;
		} else {
			return false;
		}
	}

	public function logger($message) {
		if ($this->config->get('bluepay_redirect_debug') == 1) {
			$log = new Log('bluepay_redirect.log');
			$log->write($message);
		}
	}

	public function sendCurl($url, $post_data) {
		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));

		$response_data = curl_exec($curl);
		curl_close($curl);

		return json_decode($response_data, true);
	}

}
