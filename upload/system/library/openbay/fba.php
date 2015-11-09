<?php
namespace openbay;

class fba {
	private $api_key;
	private $api_account_id;
	//private $url = 'https://api.openbaypro.io/';
	private $url = 'http://dev.openbaypro.io/';
	private $registry;

	private $logging = 1;
	private $logging_verbose = 1;
	private $max_log_size = 50;

	public function __construct($registry) {
		$this->registry = $registry;

		$this->api_key = $this->config->get('openbay_fba_api_key');
		$this->api_account_id = $this->config->get('openbay_fba_api_account_id');
		$this->logging = $this->config->get('openbay_fba_debug_log');

		$this->setLogger();
	}

	public function __get($name) {
		return $this->registry->get($name);
	}

	public function call($uri, $data = array(), $request_type = 'GET') {
		$this->log("Request: " . $request_type . " : " . $this->url . $uri);

		$headers = array();
		$headers[] = 'X-Auth-Token: ' . $this->api_key;
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'X-Account-ID: ' . $this->api_account_id;

		$defaults = array(
			CURLOPT_HTTPHEADER      => $headers,
			CURLOPT_URL             => $this->url . $uri,
			CURLOPT_USERAGENT       => 'OpenBay Pro for Fulfillment by Amazon',
			CURLOPT_FRESH_CONNECT   => 1,
			CURLOPT_RETURNTRANSFER  => 1,
			CURLOPT_FORBID_REUSE    => 1,
			CURLOPT_TIMEOUT         => 30,
			CURLOPT_SSL_VERIFYPEER  => 0,
			CURLOPT_SSL_VERIFYHOST  => 0,
		);

		if ($this->logging_verbose == 1) {
			$defaults[CURLOPT_VERBOSE] = 1;
			$defaults[CURLOPT_STDERR] = fopen(DIR_LOGS . 'fba_verbose.log', "a+");
		}

		if ($request_type == "POST") {
			$this->log('Request body:');
			$this->log(print_r($data, true));
			$defaults[CURLOPT_POST] = json_encode($data);
			$defaults[CURLOPT_POSTFIELDS] = json_encode($data);
		} else {
			$defaults[CURLOPT_CUSTOMREQUEST] = "GET";
		}

		$ch = curl_init();

		curl_setopt_array($ch, $defaults);

		$result = curl_exec($ch);

		if (!$result) {
			echo 'call() - Curl Failed ' . curl_error($ch) . ' ' . curl_errno($ch);
			$this->log('call() - Curl Failed ' . curl_error($ch) . ' ' . curl_errno($ch));

			$response = array('error' => true, 'error_messages' => array(curl_error($ch) . ' ' . curl_errno($ch)), 'body' => null, 'response_http' => 0);
		} else {
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			$this->log("Response: " . $http_code . " : " . strlen($result) . " bytes");

			$encoding = mb_detect_encoding($result);

			if ($encoding == 'UTF-8') {
				$result = preg_replace('/[^(\x20-\x7F)]*/', '', $result);
			}

			$result_parsed = json_decode($result, 1);

			$this->log('Raw json response:');
			$this->log($result);

			$this->log('Parsed response:');
			$this->log(print_r($result_parsed, true));

			$response = array(
				'error' => false,
				'error_messages' => array(),
				'body' => $result_parsed['result'],
				'response_http' => $http_code
			);

			if (!empty($result_parsed['errors'])) {
				$response['error'] = true;
				$response['error_messages'] = $result_parsed['errors'];
			}
		}

		curl_close($ch);

		return $response;
	}

	public function getServerUrl() {
		return $this->url;
	}

	public function validate() {
		if ($this->config->get('openbay_fba_api_key') && $this->config->get('openbay_fba_api_account_id')) {
			return true;
		} else {
			return false;
		}
	}

	private function setLogger() {
		if(file_exists(DIR_LOGS . 'fulfillment_by_amazon.log')) {
			if(filesize(DIR_LOGS . 'fulfillment_by_amazon.log') > ($this->max_log_size * 1000000)) {
				rename(DIR_LOGS . 'fulfillment_by_amazon.log', DIR_LOGS . '_fulfillment_by_amazon_' . date('Y-m-d_H-i-s') . '.log');
			}
		}

		$this->logger = new \Log('fulfillment_by_amazon.log');
	}

	public function log($data) {
		if (function_exists('getmypid')) {
			$process_id = getmypid();
			$data = $process_id . ' - ' . $data;
		}

		if ($this->logging == 1) {
			$this->logger->write($data);
		}
	}

	public function createFBAOrder($order_id) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "fba_order` SET `order_id` = '" . (int)$order_id . "', `status` = 0, `created` = now()");

		return $this->db->getLastId();
	}

	public function createFBAFulfillment($order_id, $request_body, $response_body, $header_code) {
//`fba_order_id` INT(11) NOT NULL,
//`created` DATETIME NOT NULL,
//`request_body` TEXT NOT NULL,
//`response_body` TEXT NOT NULL,
//`response_header_code` INT(3) NOT NULL,

		$this->db->query("
			INSERT INTO `" . DB_PREFIX . "fba_order_fulfillment`
				SET
					`order_id` = '" . (int)$order_id . "',
					`status` = 0,
					`created` = now()
		");

		return $this->db->getLastId();

	}

	public function getAllFBAOrders($filter) {
		$sql = "";

		// start date filter
		if (isset($filter['filter_start'])) {
			$sql .= " AND `created` >= '".$filter['filter_start']."'";
		}
		// end date filter
		if (isset($filter['filter_end'])) {
			$sql .= " AND `created` <= '".$filter['filter_end']."'";
		}
		// status filter
		if (isset($filter['filter_status'])) {
			$sql .= " AND `filter_status` = '".$filter['filter_status']."'";
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fba_order` WHERE 1 ".$sql);

		if ($query->num_rows == 0) {
			return false;
		} else {
			return $query->rows;
		}
	}

	public function getFBAOrder($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fba_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($query->num_rows == 0) {
			return false;
		} else {
			$fba_order = $query->row;
			$fba_order['fulfillments'] = $this->getFBAOrderFulfillments($fba_order['fba_order_id']);

			return $fba_order;
		}
	}

	public function getFBAOrderFulfillments($fba_order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fba_order_fulfillment` WHERE `fba_order_id` = '" . (int)$fba_order_id . "'");

		if ($query->num_rows == 0) {
			return false;
		} else {
			return $query->rows;
		}
	}
}