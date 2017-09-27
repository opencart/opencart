<?php
class ModelExtensionPaymentEway extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/eway');

		if ($this->config->get('payment_eway_status')) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_eway_standard_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			if (!$this->config->get('payment_eway_standard_geo_zone_id')) {
				$status = true;
			} elseif ($query->num_rows) {
				$status = true;
			} else {
				$status = false;
			}
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code' => 'eway',
				'title' => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('payment_eway_sort_order')
			);
		}

		return $method_data;
	}

	public function addOrder($order_data) {

		$cap = '';
		if ($this->config->get('payment_eway_transaction_method') == 'payment') {
			$cap = ",`capture_status` = '1'";
		}
		$this->db->query("INSERT INTO `" . DB_PREFIX . "eway_order` SET `order_id` = '" . (int)$order_data['order_id'] . "', `created` = NOW(), `modified` = NOW(), `debug_data` = '" . $this->db->escape($order_data['debug_data']) . "', `amount` = '" . $this->currency->format($order_data['amount'], $order_data['currency_code'], false, false) . "', `currency_code` = '" . $this->db->escape($order_data['currency_code']) . "', `transaction_id` = '" . $this->db->escape($order_data['transaction_id']) . "'{$cap}");

		return $this->db->getLastId();
	}

	public function addTransaction($eway_order_id, $type, $transactionid, $order_info) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "eway_transactions` SET `eway_order_id` = '" . (int)$eway_order_id . "', `created` = NOW(), `transaction_id` = '" . $this->db->escape($transactionid) . "', `type` = '" . $this->db->escape($type) . "', `amount` = '" . $this->currency->format($order_info['total'], $order_info['currency_code'], false, false) . "'");

		return $this->db->getLastId();
	}

	public function getCards($customer_id) {

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "eway_card WHERE customer_id = '" . (int)$customer_id . "'");

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

	public function checkToken($token_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "eway_card WHERE token_id = '" . (int)$token_id . "'");
		if ($query->num_rows) {
			return true;
		} else {
			return false;
		}
	}

	public function addCard($order_id, $card_data) {
		$this->db->query("INSERT into " . DB_PREFIX . "eway_card SET customer_id = '" . $this->db->escape($card_data['customer_id']) . "', order_id = '" . $this->db->escape($order_id) . "', digits = '" . $this->db->escape($card_data['Last4Digits']) . "', expiry = '" . $this->db->escape($card_data['ExpiryDate']) . "', type = '" . $this->db->escape($card_data['CardType']) . "'");
	}

	public function updateCard($order_id, $token) {
		$this->db->query("UPDATE " . DB_PREFIX . "eway_card SET token = '" . $this->db->escape($token) . "' WHERE order_id = '" . (int)$order_id . "'");
	}

	public function updateFullCard($card_id, $token, $card_data) {
		$this->db->query("UPDATE " . DB_PREFIX . "eway_card SET token = '" . $this->db->escape($token) . "', digits = '" . $this->db->escape($card_data['Last4Digits']) . "', expiry = '" . $this->db->escape($card_data['ExpiryDate']) . "', type = '" . $this->db->escape($card_data['CardType']) . "' WHERE card_id = '" . (int)$card_id . "'");
	}

	public function deleteCard($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "eway_card WHERE order_id = '" . (int)$order_id . "'");
	}

	public function getAccessCode($request) {
		if ($this->config->get('payment_eway_test')) {
			$url = 'https://api.sandbox.ewaypayments.com/AccessCodes';
		} else {
			$url = 'https://api.ewaypayments.com/AccessCodes';
		}

		$response = $this->sendCurl($url, $request);
		$response = json_decode($response);

		return $response;
	}

	public function getSharedAccessCode($request) {
		if ($this->config->get('payment_eway_test')) {
			$url = 'https://api.sandbox.ewaypayments.com/AccessCodesShared';
		} else {
			$url = 'https://api.ewaypayments.com/AccessCodesShared';
		}

		$response = $this->sendCurl($url, $request);
		$response = json_decode($response);

		return $response;
	}

	public function getAccessCodeResult($access_code) {
		if ($this->config->get('payment_eway_test')) {
			$url = 'https://api.sandbox.ewaypayments.com/AccessCode/' . $access_code;
		} else {
			$url = 'https://api.ewaypayments.com/AccessCode/' . $access_code;
		}

		$response = $this->sendCurl($url, '', false);
		$response = json_decode($response);

		return $response;
	}

	public function sendCurl($url, $data, $is_post=true) {
		$ch = curl_init($url);

		$eway_username = html_entity_decode($this->config->get('payment_eway_username'), ENT_QUOTES, 'UTF-8');
		$eway_password = html_entity_decode($this->config->get('payment_eway_password'), ENT_QUOTES, 'UTF-8');

		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		curl_setopt($ch, CURLOPT_USERPWD, $eway_username . ":" . $eway_password);
		if ($is_post) {
		curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		} else {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

		$response = curl_exec($ch);

		if (curl_errno($ch) != CURLE_OK) {
			$response = new stdClass();
			$response->Errors = "POST Error: " . curl_error($ch) . " URL: $url";
			$this->log(array('error' => curl_error($ch), 'errno' => curl_errno($ch)), 'cURL failed');
			$response = json_encode($response);
		} else {
			$info = curl_getinfo($ch);
			if ($info['http_code'] != 200) {
				$response = new stdClass();
				if ($info['http_code'] == 401 || $info['http_code'] == 404 || $info['http_code'] == 403) {
					$response->Errors = "Please check the API Key and Password";
				} else {
					$response->Errors = 'Error connecting to eWAY: ' . $info['http_code'];
				}
				$response = json_encode($response);
			}
		}

		curl_close($ch);

		return $response;
	}

}
