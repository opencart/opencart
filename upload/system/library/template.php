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
	 * Set
	 *
	 * @param    string $key
	 * @param    mixed $value
	 */
	public function set($key, $value) {
		$this->adaptor->set($key, $value);
	}

	/**
	 * Render
	 *
	 * @param    string $filename
	 * @param    string $code
	 *
	 * @return    string
	 */
	public function render($filename, $code = '') {
		return $this->adaptor->render($filename, $code);
	}
}
