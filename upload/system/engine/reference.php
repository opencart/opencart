<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
 */

/**
 * Reference class
 *
 * Class for passing vars by reference into models. The events system uses
 *
 */
class Reference {
	public $value;

	public function __construct(&$value) {
		$this->value = &$value;
	}

	public function &getValue() {
		return $this->value;
	}
}