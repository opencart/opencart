<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

final class Encryption {
	private $key;

	public function __construct($key) {
		$this->key = hash('sha256', $key, true);
	}

	public function encrypt($value) {
		return strtr(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, hash('sha256', $this->key, true), $value, MCRYPT_MODE_ECB)), '+/=', '-_,');
	}

	public function decrypt($value) {
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, hash('sha256', $this->key, true), base64_decode(strtr($value, '-_,', '+/=')), MCRYPT_MODE_ECB));
	}
}