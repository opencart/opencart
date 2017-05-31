<?php
namespace openbay;

final class fba {
	private $api_key;
	private $api_account_id;
    private $encryption_key;
    private $encryption_iv;
	private $url = 'https://api.openbaypro.io/';
	private $registry;

	private $logging = 1;
	private $logging_verbose = 1;
	private $max_log_size = 50;

	/**
	 * Status IDs =
	 * 0 = new
	 * 1 = error
	 * 2 = held
	 * 3 = shipped
	 * 4 = cancelled
	 */

	/**
	 * Type IDs =
	 * 0 = new
	 * 1 = shipping
	 * 2 = cancel
	 */

	public function __construct($registry) {
		$this->registry = $registry;

		$this->api_key = $this->config->get('openbay_fba_api_key');
		$this->api_account_id = $this->config->get('openbay_fba_api_account_id');
		$this->logging = $this->config->get('openbay_fba_debug_log');

		$this->setEncryptionKey($this->config->get('openbay_fba_encryption_key'));
		$this->setEncryptionIv($this->config->get('openbay_fba_encryption_iv'));

		if ($this->logging == 1) {
			$this->setLogger();
		}
	}

	public function __get($name) {
		return $this->registry->get($name);
	}

    public function getEncryptionKey() {
        return $this->encryption_key;
    }

	public function setEncryptionKey($key) {
	    $this->encryption_key = $key;
    }

    public function getEncryptionIv() {
        return $this->encryption_iv;
    }

    public function setEncryptionIv($encryption_iv) {
        $this->encryption_iv = $encryption_iv;
    }

	public function setApiKey($api_key) {
		$this->api_key = $api_key;
	}

	public function setAccountId($api_account_id) {
		$this->api_account_id = $api_account_id;
	}

	public function call($uri, $data = array(), $request_type = 'GET') {
		$this->log("Request: " . $request_type . " : " . $this->url . $uri);

		$headers = array();
		$headers[] = 'X-Auth-Token: ' . $this->api_key;
		$headers[] = 'X-Account-ID: ' . $this->api_account_id;
        $headers[] = 'X-Endpoint-Version: 2';
        $headers[] = 'Content-Type: application/json';

		$defaults = array(
            CURLOPT_HEADER      	=> 0,
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

		$curl = curl_init();

		curl_setopt_array($curl, $defaults);

		$result = curl_exec($curl);

		if (!$result) {
			$this->log('call() - Curl Failed ' . curl_error($curl) . ' ' . curl_errno($curl));

			$response = array('error' => true, 'error_messages' => array(curl_error($curl) . ' ' . curl_errno($curl)), 'body' => null, 'response_http' => 0);
		} else {
			$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

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
				'body' => (isset($result_parsed['result']) ? $result_parsed['result'] : ''),
				'response_http' => $http_code
			);

			if (isset($result_parsed['errors']) && !empty($result_parsed['errors'])) {
				$response['error'] = true;
				$response['error_messages'] = $result_parsed['errors'];
			}
		}

		curl_close($curl);

		return $response;
	}

	public function getServerUrl() {
		return $this->url;
	}

	public function validate() {
		if ($this->config->get('openbay_fba_api_account_id') && $this->config->get('openbay_fba_api_key') && $this->config->get('openbay_fba_encryption_key') && $this->config->get('openbay_fba_encryption_iv')) {
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
		if ($this->logging == 1) {
            if (function_exists('getmypid')) {
                $process_id = getmypid();
                $data = $process_id . ' - ' . $data;
            }

			$this->logger->write($data);
		}
	}

	public function createFBAOrderID($order_id) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "fba_order` SET `order_id` = '" . (int)$order_id . "', `status` = 0, `created` = now()");

		return $this->db->getLastId();
	}

	public function updateFBAOrderStatus($order_id, $status_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "fba_order` SET `status` = '" . (int)$status_id . "' WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");
	}

	public function updateFBAOrderRef($order_id, $ref) {
		$this->db->query("UPDATE `" . DB_PREFIX . "fba_order` SET `fba_order_fulfillment_ref` = '" . $this->db->escape($ref) . "' WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");
	}

	public function updateFBAOrderFulfillmentID($order_id, $fba_order_fulfillment_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "fba_order` SET `fba_order_fulfillment_id` = '" . (int)$fba_order_fulfillment_id . "' WHERE `order_id` = '" . (int)$order_id . "'");
	}

	public function createFBAFulfillmentID($order_id, $type) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "fba_order_fulfillment` SET `created` = now(), `order_id` = '" . (int)$order_id . "', `type` = '" . (int)$type . "'");

		$id = $this->db->getLastId();

		$this->db->query("UPDATE `" . DB_PREFIX . "fba_order` SET `fba_order_fulfillment_id` = '" . (int)$id . "' WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		return $id;
	}

	public function populateFBAFulfillment($request_body, $response_body, $header_code, $fba_order_fulfillment_id) {
		$this->db->query("
			UPDATE `" . DB_PREFIX . "fba_order_fulfillment`
				SET
					`request_body` = '" . $this->db->escape($request_body) . "',
					`response_body` = '" . $this->db->escape($response_body) . "',
					`response_header_code` = '" . (int)$header_code . "'
				WHERE
					`fba_order_fulfillment_id` = '" . (int)$fba_order_fulfillment_id . "'
		");

		$insert_id = $this->db->getLastId();

		return $insert_id;
	}

	public function getFBAOrders($filter) {
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
			$sql .= " AND `status` = '".$filter['filter_status']."'";
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fba_order` WHERE 1 ".$sql." ORDER BY `created` DESC");

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
			$fba_order['fulfillments'] = $this->getFBAOrderFulfillments($order_id);

			return $fba_order;
		}
	}

	public function getFBAOrderByRef($ref) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fba_order` WHERE `fba_order_fulfillment_ref` = '" . $this->db->escape($ref) . "' LIMIT 1");

		if ($query->num_rows == 0) {
			return false;
		} else {
			$fba_order = $query->row;
			$fba_order['fulfillments'] = $fba_order['order_id'];

			return $fba_order;
		}
	}

	public function getFBAOrderFulfillments($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fba_order_fulfillment` WHERE `order_id` = '" . (int)$order_id . "' ORDER BY `created` DESC");

		if ($query->num_rows == 0) {
			return false;
		} else {
			return $query->rows;
		}
	}

	public function hasOrderFBAItems($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order_product` `op` LEFT JOIN `" . DB_PREFIX . "product` `p` ON `op`.`product_id` = `p`.`product_id` WHERE `p`.`location` = 'FBA' AND `op`.`order_id` = '".(int)$order_id."'");

		if ($query->num_rows == 0) {
			return false;
		} else {
			return $query->row['total'];
		}
	}
}
