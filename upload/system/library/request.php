<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* Request class
*/
class Request {
	public $get = array();
	public $post = array();
	public $request = array();
	public $cookie = array();
	public $files = array();
	public $server = array();
	
	/**
	 * Constructor
 	*/
	public function __construct() {
		$this->get = $this->clean($_GET);
		$this->post = $this->clean($_POST);
		$this->request = $this->clean($_REQUEST);
		$this->cookie = $this->clean($_COOKIE);
		$this->files = $this->clean($_FILES);
		$this->server = $this->clean($_SERVER);
	}
	
	/**
     * 
	 * @param	mixed	$data
	 * @param	bool	$trim
	 *
     * @return	array
     */
	public function clean($data,$trim=true) {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				unset($data[$key]);
				if (($key=='symbol_left' || $key=='symbol_right') && is_string($value)) {
					$data[$this->clean($key,false)] = $this->clean($value,false);
				} else {
					$data[$this->clean($key,$trim)] = $this->clean($value,$trim);
				}
			}
		} else {
			$data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
			if ($trim) {
				$data = trim($data);
			}
		}

		return $data;
	}
}