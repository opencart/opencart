<?php
class GooglePay {
	private $config;
	private $registry;
	private $log;

	const API_URL = '';
	const API_VERSION_MAJOR = 2;
	const API_VERSION_MINOR = 0;
	const MERCHANT_ID = 123456789;

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->log = $registry->get('log');
		$this->registry = $registry;
	}

	public function debug($text) {
		if ($this->config->get('payment_google_pay_debug')) {
			$this->log->write($text);
		}
	}

	public function test() {
		die("hello");
	}
}