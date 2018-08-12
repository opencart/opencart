<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Template class
*/
class Template {
	private $adaptor;

	/**
	 * Constructor
	 *
	 * @param    string $adaptor
	 *
	 */
	public function __construct($adaptor) {
		$class = 'Template\\' . $adaptor;

		if (class_exists($class)) {
			$this->adaptor = new $class();
		} else {
			throw new \Exception('Error: Could not load template adaptor ' . $adaptor . '!');
		}
	}

	/**
	 *
	 *
	 * @param    mixed $value
	 */
	public function addFilter($key, $value) {
		$this->adaptor->addFilter($key, $value);
	}

	/**
	 *
	 *
	 * @param    string $key
	 * @param    mixed $value
	 */
	public function set($key, $value) {
		$this->adaptor->set($key, $value);
	}

	/**
	 *
	 *
	 * @param    string $template
	 * @param    bool $cache
	 *
	 * @return    string
	 */
	public function render($template, $cache = false) {
		return $this->adaptor->render($template, $cache);
	}
}
