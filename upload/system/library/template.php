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
namespace Opencart\System\Library;
/**
 * Class Template
 */
class Template {
	/**
	 * @var object
	 */
	private object $adaptor;

	/**
	 * Constructor
	 *
	 * @param string $adaptor
	 */
	public function __construct(string $adaptor) {
		$class = 'Opencart\System\Library\Template\\' . $adaptor;

		if (!class_exists($class)) {
			throw new \Exception('Error: Could not load template adaptor ' . $adaptor . '!');
		}

		$this->adaptor = new $class();
	}

	/**
	 * Add Path
	 *
	 * @param string $namespace
	 * @param string $directory
	 *
	 * @return void
	 */
	public function addPath(string $namespace, string $directory = ''): void {
		$this->adaptor->addPath($namespace, $directory);
	}

	/**
	 * Render
	 *
	 * @param string               $filename
	 * @param array<string, mixed> $data
	 * @param string               $code
	 *
	 * @return string
	 */
	public function render(string $filename, array $data = [], string $code = ''): string {
		return $this->adaptor->render($filename, $data, $code);
	}
}
