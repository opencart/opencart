<?php
final class Encryption {
	public function encrypt($value, $key) {
		return strtr(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, hash('sha256', $key, true), $value, MCRYPT_MODE_ECB)), '+/=', '-_,');
	}

	public function decrypt($value, $key) {
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, hash('sha256', $key, true), base64_decode(strtr($value, '-_,', '+/=')), MCRYPT_MODE_ECB));
	}
}
?>