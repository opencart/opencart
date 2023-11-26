<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/
namespace Opencart\System\Engine;
/**
 * Class Registry
 *
 * @property \Opencart\System\Engine\Config $config
 * @property \Opencart\System\Engine\Event $event
 * @property \Opencart\System\Engine\Loader $load
 * @property \Opencart\System\Engine\Registry $autoloader
 * @property \Opencart\System\Library\Cache $cache
 * @property \Opencart\System\Library\Cart\Cart $cart
 * @property \Opencart\System\Library\Cart\Currency $currency
 * @property \Opencart\System\Library\Cart\Customer $customer
 * @property \Opencart\System\Library\Cart\Length $length
 * @property \Opencart\System\Library\Cart\Tax $tax
 * @property \Opencart\System\Library\Cart\Weight $weight
 * @property \Opencart\System\Library\DB $db
 * @property \Opencart\System\Library\Document $document
 * @property \Opencart\System\Library\Language $language
 * @property \Opencart\System\Library\Log $log
 * @property \Opencart\System\Library\Request $request
 * @property \Opencart\System\Library\Response $response
 * @property \Opencart\System\Library\Session $session
 * @property \Opencart\System\Library\Template $template
 * @property \Opencart\System\Library\Url $url
 * @property ?\Opencart\System\Library\Cart\User $user
 */
class Registry {
	/**
	 * @var array
	 */
	private array $data = [];

	/**
	 * __get
	 *
	 * https://www.php.net/manual/en/language.oop5.overloading.php#object.get
	 *
	 * @param    string  $key
	 *
	 * @return   ?object
	 */
	public function __get(string $key): ?object {
		return $this->get($key);
	}

	/**
	 * __set
	 *
	 * https://www.php.net/manual/en/language.oop5.overloading.php#object.set
	 *
	 * @param    string  $key
	 * @param    object  $value
	 *
	 * @return   void
	 */
	public function __set(string $key, object $value): void {
		$this->set($key, $value);
	}

	/**
	 * __isset
	 *
	 * https://www.php.net/manual/en/language.oop5.overloading.php#object.set
	 *
	 * @param    string  $key
	 *
	 * @return   bool
	 */
	public function __isset(string $key): bool {
		return $this->has($key);
	}

	/**
     * Get
     *
     * @param string $key
     *
     * @return ?object
     */
	public function get(string $key): ?object {
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}

    /**
     * Set
     *
     * @param string $key
     * @param object $value
     *
     * @return void
     */
	public function set(string $key, object $value): void {
		$this->data[$key] = $value;
	}
	
    /**
     * Has
     *
     * @param string $key
     *
     * @return bool
     */
	public function has(string $key): bool {
		return isset($this->data[$key]);
	}

	/**
     * Unset
     *
     * Unsets registry value by key.
     *
     * @param string $key
     *
     * @return void
     */
	public function unset(string $key): void {
		if (isset($this->data[$key])) {
			unset($this->data[$key]);
		}
	}
}
