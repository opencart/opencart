<?php

use Cardinity\Client;
use Cardinity\Method\Payment;
use Cardinity\Exception as CardinityException;

class ModelExtensionPaymentCardinity extends Model {
	
	public function addOrder($data) {

		$orderByThisId= $this->getOrder($data['order_id']);
		if ($orderByThisId && $orderByThisId['payment_status'] != 'failed_3dsv1') {
			//avoid creating duplicate order by same id	
		}else{
			$this->db->query("INSERT INTO `" . DB_PREFIX . "cardinity_order` SET `order_id` = '" . (int)$data['order_id'] . "', `payment_id` = '" . $this->db->escape($data['payment_id']) . "'");
		}
		
	}

	public function getOrder($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "cardinity_order` WHERE `order_id` = '" . (int)$order_id . "' ORDER BY cardinity_order_id DESC LIMIT 1");

		return $query->row;
	}


	public function updateOrder($data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "cardinity_order` SET `payment_status` = '" . $this->db->escape($data['payment_status']) . "' WHERE `payment_id` = '" . $this->db->escape($data['payment_id']) . "'");
	}


	public function createPayment($key, $secret, $payment_data) {
		$client = Client::create(array(
			'consumerKey'    => $key,
			'consumerSecret' => $secret,
		));

		$method = new Payment\Create($payment_data);

		try {
			$payment = $client->call($method);

			return $payment;
		} catch (Exception $exception) {
			$this->exception($exception);

			throw $exception;
		}
	}

	public function getPayment($key, $secret, $payment_id){
		$client = Client::create(array(
			'consumerKey'    => $key,
			'consumerSecret' => $secret,
		));

		$method = new Payment\Get($payment_id);

		try {
			$payment = $client->call($method);

			return $payment;
		} catch (Exception $exception) {
			$this->exception($exception);

			throw $exception;
		}
	}

	public function createExternalPayment($project_key, $project_secret, $payment_data) {
		$attributes = [
			"amount" => $payment_data['amount'],
			"currency" => $payment_data['currency'],
			"country" => $payment_data['country'],
			"order_id" => $payment_data['order_id'],
			"description" => $payment_data['description'],
			"project_id" => $project_key,
			"return_url" => $payment_data['return_url'],
            "notification_url" => $payment_data['notification_url'],
		];
        if(isset($payment_data['email_address'])){
            $attributes['email_address'] = $payment_data['email_address'];
        }
        if(isset($payment_data['mobile_phone_number'])){
            $attributes['mobile_phone_number'] = $payment_data['mobile_phone_number'];
        }

		ksort($attributes);

		$message = '';
		foreach($attributes as $key => $value) {
			$message .= $key.$value;
		}

		$attributes['signature'] = hash_hmac('sha256', $message, $project_secret);

		return $attributes;
	}

	public function storeSession($data) {

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "cardinity_session` WHERE `session_id` = '" . $data['session_id'] . "' ORDER BY `cardinity_session_id` ASC  LIMIT 1");

		if($query->num_rows){
			$this->db->query("UPDATE  `" . DB_PREFIX . "cardinity_session` SET `session_data` = '" .  $this->db->escape($data['session_data'])  . "' WHERE `session_id` = '" . $data['session_id'] . "'");
		}else{
			$this->db->query("INSERT INTO `" . DB_PREFIX . "cardinity_session` SET `session_id` = '" . $data['session_id'] . "', `session_data` = '" . $this->db->escape($data['session_data']) . "'");
		}

	}

	public function fetchSession($sessionId) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "cardinity_session` WHERE `session_id` = '" . $sessionId . "' ORDER BY `cardinity_session_id` ASC  LIMIT 1");

		return $query->row;
	}

	public function finalizePayment($key, $secret, $payment_id, $pares) {
		$client = Client::create(array(
			'consumerKey'    => $key,
			'consumerSecret' => $secret,
		));

		$method = new Payment\Finalize($payment_id, $pares);

		try {
			$payment = $client->call($method);

			return $payment;
		} catch (Exception $exception) {
			$this->exception($exception);

			return false;
		}
	}

	public function finalize3dv2Payment($key, $secret, $payment_id, $cres) {
		$client = Client::create(array(
			'consumerKey'    => $key,
			'consumerSecret' => $secret,
		));

		$method = new Payment\Finalize($payment_id, $cres, true);

		try {
			$payment = $client->call($method);

			return $payment;
		} catch (Exception $exception) {
			$this->exception($exception);

			return false;
		}
	}

	public function getMethod($address, $total) {
		$this->load->language('extension/payment/cardinity');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_cardinity_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('payment_cardinity_total') > 0 && $this->config->get('payment_cardinity_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payment_cardinity_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'		 => 'cardinity',
				'title'		 => $this->language->get('text_title'),
				'terms'		 => '',
				'sort_order' => $this->config->get('payment_cardinity_sort_order')
			);
		}

		return $method_data;
	}

	
	/**
	 * Encode data to Base64URL
	 * @param string $data
	 * @return bool|string
	 */
	function encodeBase64Url($data)
	{
		$b64 = base64_encode($data);

		if ($b64 === false) {
			return false;
		}

		// Convert Base64 to Base64URL by replacing “+” with “-” and “/” with “_”
		$url = strtr($b64, '+/', '-_');

		// Remove padding character from the end of line and return the Base64URL result
		return rtrim($url, '=');
	}

	/**
	 * Decode data from Base64URL
	 * @param string $data
	 * @param bool $strict
	 * @return bool|string
	 */
	function decodeBase64url($data, $strict = false)
	{
		// Convert Base64URL to Base64 by replacing “-” with “+” and “_” with “/”
		$b64 = strtr($data, '-_', '+/');

		return base64_decode($b64, $strict);
	}


	public function log($data, $class_step = 6, $function_step = 6) {
		if ($this->config->get('payment_cardinity_debug')) {
			$backtrace = debug_backtrace();
			$log = new Log('cardinity.log');
			$log->write('(' . $backtrace[$class_step]['class'] . '::' . $backtrace[$function_step]['function'] . ') - ' . print_r($data, true));
		}
	}

	private function exception(Exception $exception) {
		$this->log($exception->getMessage(), 1, 2);

		switch (true) {
			case $exception instanceof CardinityException\Request:
				if ($exception->getErrorsAsString()) {
					$this->log($exception->getErrorsAsString(), 1, 2);
				}

				break;
			case $exception instanceof CardinityException\InvalidAttributeValue:
				foreach ($exception->getViolations() as $violation) {
					$this->log($violation->getMessage(), 1, 2);
				}

				break;
		}
	}


	public function logTransaction($data) {
		$logFile = 'crd-transactions-'.date('Y-n').'.log';		
		$log = new Log($logFile);
		$log->write($data);
	}
}
