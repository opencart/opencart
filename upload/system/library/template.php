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
	 * @param	string	$file
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
	 * Description
	 *
	 * @param	int		$level
 	*/	
	public function set($key, $value) {
		$this->adaptor->set($key, $value);
	}
	
	/**
	 * Description
	 *
	 * @param	int		$level
 	*/	
	public function render($template, $cache = false) {
		return $this->adaptor->render($template, $cache);
	}
}
