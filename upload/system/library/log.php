<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Log class
*/
namespace Opencart\System\Library;
class Log {
	private $handle;
	
	/**
	 * Constructor
	 *
	 * @param	string	$filename
 	*/
	public function __construct($filename) {
		$file = DIR_LOGS . $filename;

		if (!is_file($file)) {
			$this->handle = fopen(DIR_LOGS . $filename, 'x');
		} else {
			$this->handle = fopen(DIR_LOGS . $filename, 'a');
		}
	}
	
	/**
     * 
     *
     * @param	string	$message
     */
	public function write($message) {
		fwrite($this->handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . "\n");
	}
	
	/**
     * 
     *
     */
	public function __destruct() {
		fclose($this->handle);
	}
}