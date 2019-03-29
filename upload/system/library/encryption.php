<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Encryption class
*/
final class Encryption {
	private $cipher;
	private $iv;
	private $iv_length;

    public function __construct() {
		$this->cipher = 'aes-128-cbc';
		$this->iv_length = openssl_cipher_iv_length($this->cipher);
		$this->iv = openssl_random_pseudo_bytes($this->iv_length);
    }
	
	/**
     * 
     *
     * @param	string	$key
	 * @param	string	$value
	 * 
	 * @return	string
     */	
	public function encrypt($key, $value) {
	    return strtr(base64_encode($this->iv . openssl_encrypt($value, $this->cipher, hash('sha256', $key, true), 0, $this->iv)), '+/=', '-_,');
	}
	
	/**
     * 
     *
     * @param	string	$key
	 * @param	string	$value
	 * 
	 * @return	string
     */
	public function decrypt($key, $value) {
		$mixed = base64_decode(strtr($value, '-_,', '+/='));
		$iv = substr($mixed, 0, $this->iv_length);
		$encrypted = substr($mixed, $this->iv_length);

		return trim(openssl_decrypt($encrypted, $this->cipher, hash('sha256', $key, true), 0, $iv));
	}
}
