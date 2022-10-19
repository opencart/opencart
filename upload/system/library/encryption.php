<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr, Billy Noah
 * @copyright	Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Encryption class
*/
namespace Opencart\System\Library;
class Encryption {
	private string $cipher = 'aes-256-ctr';
	private string $digest = 'sha256';
	
    /**
     * 
     *
     * @param	string	$key
     * @param	string	$value
     * 
     * @return	string
     */	
	public function encrypt(string $key, string $value): string {
		$key       = openssl_digest($key, $this->digest, true);
		$iv_length = openssl_cipher_iv_length($this->cipher);
		$iv        = openssl_random_pseudo_bytes($iv_length);

		return base64_encode($iv . openssl_encrypt($value, $this->cipher, $key, OPENSSL_RAW_DATA, $iv));
	}
	
    /**
     * 
     *
     * @param	string	$key
     * @param	string	$value
     * 
     * @return	string
     */
	public function decrypt(string $key, string $value): string {
		$result    = '';

		$key       = openssl_digest($key, $this->digest, true);
		$iv_length = openssl_cipher_iv_length($this->cipher);
		$value     = base64_decode($value);
		$iv        = substr($value, 0, $iv_length);
		$value     = substr($value, $iv_length);

		if (strlen($iv) == $iv_length) {
			$result = openssl_decrypt($value, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);
		}

		return $result;
	}
}
