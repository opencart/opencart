<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/
namespace Opencart\System\Library;
/**
 * Class Log
 */
class Log {
	/**
	 * @var string
	 */
	private string $file;

	/**
	 * Constructor
	 *
	 * @param	string	$filename
 	*/
	public function __construct(string $filename) {
		$this->file = DIR_LOGS . $filename;
	}
	
	/**
     * Write
     *
     * @param	string	$message
	 *
	 * @return  void
     */
	public function write(string|array $message): void {
		file_put_contents($this->file, date('Y-m-d H:i:s') . ' - ' . print_r($message, true) . "\n", FILE_APPEND);
	}
}
