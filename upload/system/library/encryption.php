<?php
final class Encryption {
	private $key;

	public function __construct($key) {
		$this->key = hash('sha256', $key, true);
	}

	public function encrypt($value) {
		return base64_encode(openssl_encrypt($value, 'AES-256-CBC', hash('sha256', $this->key, true), 0, substr(hash('sha256', $this->key, true), 16, 16)));
	}

	public function decrypt($value) {
		return openssl_decrypt(base64_decode($value), 'AES-256-CBC', hash('sha256', $this->key, true), 0, substr(hash('sha256', $this->key, true), 16, 16));
	}
}
