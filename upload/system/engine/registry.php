<?php
/**
 * @package		OpenCart
 *
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 *
 * @see		https://www.opencart.com
 */
namespace Opencart\System\Engine;
/**
 * Class Registry
 */
class Registry {
	/**
	 * @var array<string, object>
	 */
	private array $data = [];

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
	 * __set
	 *
	 * https://www.php.net/manual/en/language.oop5.overloading.php#object.set
	 *
	 * @param string $key
	 * @param object $value
	 *
	 * @return void
	 */
	public function __set(string $key, object $value): void {
		$this->set($key, $value);
	}

	/**
	 * __isset
	 *
	 * https://www.php.net/manual/en/language.oop5.overloading.php#object.set
	 *
	 * @param string $key
	 *
	 * @return bool
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
		return $this->data[$key] ?? null;
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
		unset($this->data[$key]);
	}
}
