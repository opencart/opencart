<?php
final class Encryption {
	private $key;
	
	public function __construct($key) {
        $this->key = $key;
	}
	
	public function encrypt($value, $key = '') {
		if (!$key) {
			$key = $this->key;
		}
		
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $value, MCRYPT_MODE_ECB);
	}
	
	public function decrypt($value, $key = '') {
		if (!$key) {
			$key = $this->key;
		}
				
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $value, MCRYPT_MODE_ECB);
	}
}
?>