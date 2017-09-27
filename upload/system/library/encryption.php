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
	/**
     * 
     *
     * @param	string	$key
	 * @param	string	$value
	 * 
	 * @return	string
     */	
	public function encrypt($key, $value) {
		return strtr(base64_encode(openssl_encrypt($value, 'aes-128-cbc', hash('sha256', $key, true))), '+/=', '-_,');
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
		return trim(openssl_decrypt(base64_decode(strtr($value, '-_,', '+/=')), 'aes-128-cbc', hash('sha256', $key, true)));
	}
}