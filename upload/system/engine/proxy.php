<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
 */

/**
 * Proxy class
 */
namespace Opencart\System\Engine;
class Proxy {
	/**
	 * Magic Method Get
	 *
	 * @param	string	$key
	 */
	public function &__get(string $key): object|null {
		if (property_exists($this, $key)) {
			return $this->registry->get($key);
		} else {
			throw new \Exception('Error: Could not call proxy key ' . $key . '!');
		}
	}

	/**
	 * Magic Method Set
	 *
	 * @param	string	$key
	 * @param	string	$value
	 */
	public function __set(string $key, object $value): void {
		$this->{$key} = $value;
	}

	public function __call(string $method, array $args): mixed {
		// Hack for pass-by-reference
		foreach ($args as $key => &$value);

		if (isset($this->{$method})) {
			return call_user_func_array($this->{$method}, $args);
		} else {
			$trace = debug_backtrace();

			throw new \Exception('<b>Notice</b>:  Undefined property: Proxy::' . $method . ' in <b>' . $trace[0]['file'] . '</b> on line <b>' . $trace[0]['line'] . '</b>');
		}
	}
}