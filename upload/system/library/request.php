<?php
final class Request {
	public $get = array();
	public $post = array();
	public $cookie = array();
	public $files = array();
	public $server = array();
	
  	public function __construct() {
		if (ini_get('register_globals')) {
			$array = array('_REQUEST', '_SERVER', '_ENV', '_FILES');
	
			foreach ($array as $value) {
				foreach ($GLOBALS[$value] as $key => $var) {
					if ($var === $GLOBALS[$key]) {
						unset($GLOBALS[$key]);
					}
				}
			}
		}

		$this->get    =& $this->clean($_GET);
		$this->post   =& $this->clean($_POST);
		$this->cookie =& $this->clean($_COOKIE);
		$this->files  =& $this->clean($_FILES);
		$this->server =& $this->clean($_SERVER);
	}
	
  	public function clean($data) {
    	if (is_array($data)) {
	  		foreach ($data as $key => $value) {
	    		$data[$key] =& $this->clean($value);
	  		}
		} else {
	  		$data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
		}
	
		return $data;
	}
}
?>