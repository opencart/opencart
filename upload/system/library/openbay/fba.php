<?php
namespace openbay;

//curl_setopt($curl, , "DELETE");

class fba {
	private $api_key;
	private $api_account_id;
	//private $url = 'https://api.openbaypro.io/';
	private $url = 'http://apiv3.welfordlocal.co.uk/';
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

		if ($request_type = ("POST")) {
			$defaults[CURLOPT_POST] = json_encode($data);
			$defaults[CURLOPT_POSTFIELDS] = json_encode($data);
		} else {
			$defaults[CURLOPT_CUSTOMREQUEST] = "GET";
		}

		$ch = curl_init();

		curl_setopt_array($ch, $defaults);

		if (! $result = curl_exec($ch)) {
			echo 'call() - Curl Failed ' . curl_error($ch) . ' ' . curl_errno($ch);
			$this->log('call() - Curl Failed ' . curl_error($ch) . ' ' . curl_errno($ch));

			$response = array('error' => true, 'body' => null);
		} else {
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			$this->log("Response: " . $http_code . " : " . strlen($result) . " bytes");

			$encoding = mb_detect_encoding($result);

			if ($encoding == 'UTF-8') {
				$result = preg_replace('/[^(\x20-\x7F)]*/', '', $result);
			}

			$response = array('error' => false, 'body' => json_decode($result, 1));
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
}