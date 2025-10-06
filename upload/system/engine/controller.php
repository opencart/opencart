<?php
/**
 * @package      OpenCart
 *
 * @author       Daniel Kerr
 * @copyright    Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license      https://opensource.org/licenses/GPL-3.0
 *
 * @see         https://www.opencart.com
 */
namespace Opencart\System\Engine;
/**
 * Class Controller
 *
 * @mixin \Opencart\System\Engine\Registry
 */
class Controller {
	/**
	 * @var \Opencart\System\Engine\Registry
	 */
	protected \Opencart\System\Engine\Registry $registry;

	/**
	 * Constructor
	 *
	 * @param \Opencart\System\Engine\Registry $registry
	 */
	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->registry = $registry;
	}

	/**
	 * __get
	 *
	 * @param string $key
	 *
	 * @return object
	 */
	public function __get(string $key): object {
		if (!$this->registry->has($key)) {
			throw new \Exception('Error: Could not call registry key ' . $key . '!');
		}

		return $this->registry->get($key);
	}

	/**
	 * __set
	 *
	 * @param string $key
	 * @param object $value
	 *
	 * @return void
	 */
	public function __set(string $key, object $value): void {
		$this->registry->set($key, $value);
	}
}
