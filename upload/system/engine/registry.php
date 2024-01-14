<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Registry class
 *
 * @property Cache                         $cache
 * @property Cart\Cart                     $cart
 * @property Cart\Currency                 $currency
 * @property Cart\Customer                 $customer
 * @property Cart\Length                   $length
 * @property Cart\Tax                      $tax
 * @property ?Cart\User                    $user
 * @property Cart\Weight                   $weight
 * @property Config                        $config
 * @property Config                        $setting
 * @property DB                            $db
 * @property Document                      $document
 * @property Encryption                    $encryption
 * @property Event                         $event
 * @property googleshopping\Googleshopping $googleshopping
 * @property Language                      $language
 * @property Loader                        $load
 * @property Log                           $log
 * @property Request                       $request
 * @property Response                      $response
 * @property Session                       $session
 * @property ?Squareup                     $squareup
 * @property Url                           $url
*/
final class Registry {
	private $data = array();

	/**
	 * __get
	 *
	 * https://www.php.net/manual/en/language.oop5.overloading.php#object.get
	 *
	 * @param string $key
	 *
	 * @return ?object
	 */
	public function __get(string $key): ?object {
		return $this->get($key);
	}

	/**
     * 
     *
     * @param	string	$key
	 * 
	 * @return	mixed
     */
	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : null);
	}

    /**
     * 
     *
     * @param	string	$key
	 * @param	string	$value
     */	
	public function set($key, $value) {
		$this->data[$key] = $value;
	}
	
    /**
     * 
     *
     * @param	string	$key
	 *
	 * @return	bool
     */
	public function has($key) {
		return isset($this->data[$key]);
	}
}