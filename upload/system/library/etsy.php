<?php
final class Etsy {
	private $registry;
	private $url    = 'http://etsy.welfordlocal.co.uk/';

	public function __construct($registry) {
		$this->registry = $registry;
		$this->token = $this->config->get('etsy_token');
		$this->enc1 = $this->config->get('etsy_enc1');
		$this->enc2 = $this->config->get('etsy_enc2');
		$this->logging = $this->config->get('etsy_logging');

		$this->load->library('log');
		$this->logger = new Log('etsylog.log');
	}

	public function __get($name) {
		return $this->registry->get($name);
	}

	public function log($data, $write = true) {
			if(function_exists('getmypid')) {
				$pId = getmypid();
				$data = $pId . ' - ' . $data;
			}

			if($write == true) {
				$this->logger->write($data);
			}
	}

	public function getApiServer() {
		return $this->url;
	}

	public function call($uri, $method, $data = array()) {
		if($this->config->get('etsy_status') == 1) {

			$headers = array ();
			$headers[] = 'X-Auth-Token: '.$this->token;
			$headers[] = 'X-Auth-Enc: '.$this->enc1;
			$headers[] = 'Content-Type: application/json';
			//$headers[] = 'Content-Length: '.strlen(json_encode($data));

			$defaults = array(
				CURLOPT_HEADER      	=> 0,
				CURLOPT_HTTPHEADER      => $headers,
				CURLOPT_URL             => $this->url.$uri,
				CURLOPT_USERAGENT       => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1",
				CURLOPT_FRESH_CONNECT   => 1,
				CURLOPT_RETURNTRANSFER  => 1,
				CURLOPT_FORBID_REUSE    => 1,
				CURLOPT_TIMEOUT         => 10,
				CURLOPT_SSL_VERIFYPEER  => 0,
				CURLOPT_SSL_VERIFYHOST  => 0,
				CURLOPT_VERBOSE 		=> true,
				CURLOPT_STDERR 			=> fopen(DIR_LOGS.'curl_verbose.log', "w+")
			);

			if ($method == 'POST') {
				$defaults[CURLOPT_POST] = 1;
				$defaults[CURLOPT_POSTFIELDS] = json_encode($data);
			}

			$ch = curl_init();
			curl_setopt_array($ch, $defaults);

			$response = array();

			if( ! $result = curl_exec($ch)) {
				$this->log('call() - Curl Failed ' . curl_error($ch) . ' ' . curl_errno($ch));

				return false;
			} else {
				$this->log('call() - Result of : "' . print_r($result, true) . '"');

				$encoding = mb_detect_encoding($result);

				if($encoding == 'UTF-8') {
					$result = preg_replace('/[^(\x20-\x7F)]*/','', $result);
				}

				$result = json_decode($result, 1);

				$response['header_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

				if(!empty($result)) {
					$response['data'] = $result;
				}else{
					$response['data'] = '';
				}
			}

			curl_close($ch);

			return $response;
		}else{
			$this->log('call() - OpenBay Pro / Etsy not active');

			return false;
		}
	}

	public function settingsUpdate() {
		$this->log('Etsy loadDataTypes() start');

		$response = $this->call('data/type/getSetup', 'GET');

		foreach ($response['data'] as $key => $options) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "etsy_setting_option` WHERE  `key` = '".$this->db->escape($key)."' LIMIT 1");

			$this->db->query("INSERT INTO `" . DB_PREFIX . "etsy_setting_option` SET `data` = '" . $this->db->escape(serialize($options)) . "', `key` = '".$this->db->escape($key)."', `last_updated`  = now()");

			$this->log('Updated Etsy option: '.$key);
		}

		$this->log('Etsy loadDataTypes() complete');
	}

	public function getSetting($key) {
		$qry = $this->db->query("SELECT `data` FROM `" . DB_PREFIX . "etsy_setting_option` WHERE `key` = '" . $this->db->escape($key) . "' LIMIT 1");

		if($qry->num_rows > 0) {
			return unserialize($qry->row['data']);
		}else{
			return false;
		}
	}
}