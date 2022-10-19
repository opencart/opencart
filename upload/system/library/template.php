<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Template class
*/
namespace Opencart\System\Library;
class Template {
	private object $adaptor;

	/**
	 * Constructor
	 *
	 * @param    string $adaptor
	 *
	 */
	public function __construct(string $adaptor) {
		$class = 'Opencart\System\Library\Template\\' . $adaptor;

		if (class_exists($class)) {
			$this->adaptor = new $class();
		} else {
			throw new \Exception('Error: Could not load template adaptor ' . $adaptor . '!');
		}
	}

	/**
	 * addPath
	 *
	 * @param    string $namespace
	 * @param    string $directory
	 */
	public function addPath(string $namespace, string $directory = ''): void {
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
	public function render(string $filename, array $data = [], string $code = ''): string {
		return $this->adaptor->render($filename, $data, $code);
	}
}
