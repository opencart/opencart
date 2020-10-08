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
namespace Opencart\System\Library;
class Template {
	private $adaptor;

	/**
	 * Constructor
	 *
	 * @param    string $adaptor
	 *
	 */
	public function __construct($adaptor) {
		$class = 'Opencart\System\Library\Template\\' . $adaptor;

		if (class_exists($class)) {
			$this->adaptor = new $class();
		} else {
			error_log('Error: Could not load template adaptor ' . $adaptor . '!');
		}
	}

	/**
	 * addPath
	 *
	 * @param    string $namespace
	 * @param    string $directory
	 */
	public function addPath($namespace, $directory = '') {
		$this->adaptor->addPath($namespace, $directory);
	}

	/**
	 * Render
	 *
	 * @param    string $filename
	 * @param	 array	$data
	 * @param    string $code
	 *
	 * @return    string
	 */
	public function render($filename, $data = [], $code = '') {
		return $this->adaptor->render($filename, $data, $code);
	}
}
