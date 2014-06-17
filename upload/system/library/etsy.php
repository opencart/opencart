<?php
final class Etsy {
	private $registry;
	private $url    = 'https://etsy.welfordlocal.co.uk/';

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
		if($this->logging == 1) {
			if(function_exists('getmypid')) {
				$pId = getmypid();
				$data = $pId . ' - ' . $data;
			}

			if($write == true) {
				$this->logger->write($data);
			}
		}
	}

	public function getApiServer() {
		return $this->url;
	}
}