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
			$headers[] = 'Content-Type:application/json';

			echo '<pre>';
			print_r($headers);

			echo $this->url.$uri;

			$defaults = array(
				CURLOPT_HEADER      	=> 1,
				CURLOPT_HTTPHEADER      => $headers,
				CURLOPT_URL             => $this->url.$uri,
				CURLOPT_USERAGENT       => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1",
				CURLOPT_FRESH_CONNECT   => 1,
				CURLOPT_RETURNTRANSFER  => 1,
				CURLOPT_FORBID_REUSE    => 1,
				CURLOPT_TIMEOUT         => 0,
				CURLOPT_SSL_VERIFYPEER  => 0,
				CURLOPT_SSL_VERIFYHOST  => 0,
				//CURLOPT_VERBOSE 		=> true,
				//CURLOPT_STDERR 			=> fopen('C:\xampp\htdocs\projects\test\openbay2\system\logs\veb.log', "w+")
			);

			if ($method == 'POST') {
				$defaults['CURLOPT_POST'] = 1;
				$defaults['CURLOPT_POSTFIELDS'] = http_build_query($data, '', "&");
			}

			$ch = curl_init();
			curl_setopt_array($ch, $defaults);
			if( ! $result = curl_exec($ch)) {
				$this->log('call() - Curl Failed ' . curl_error($ch) . ' ' . curl_errno($ch));
			}
			curl_close($ch);

			$this->log('call() - Result of : "' . print_r($result, true) . '"');

			$encoding = mb_detect_encoding($result);

			if($encoding == 'UTF-8') {
				$result = preg_replace('/[^(\x20-\x7F)]*/','', $result);
			}

			$result = json_decode($result, 1);

			echo '<pre>';
			print_r($result);
			die();

			if(!empty($result)) {
				return $result;
			}else{
				return false;
			}
		}else{
			$this->log('call() - OpenBay Pro / Etsy not active');
		}
	}
}