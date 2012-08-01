<?php
class Request {
	public $get = array();
	public $post = array();
	public $cookie = array();
	public $files = array();
	public $server = array();

  	public function __construct() {
		$_GET = $this->clean($_GET);
		$_POST = $this->clean($_POST);
		$_REQUEST = $this->clean($_REQUEST);
		$_COOKIE = $this->clean($_COOKIE);
		$_FILES = $this->clean($_FILES);
		$_SERVER = $this->clean($_SERVER);

		$this->get = $_GET;
		$this->post = $_POST;
		$this->request = $_REQUEST;
		$this->cookie = $_COOKIE;
		$this->files = $_FILES;
		$this->server = $_SERVER;
	}

	public function get($index) {
		if(isset($this->get[$index])) {
			return $this->get[$index];
		}
		return null;
	}

	public function post($index) {
		if(isset($this->post[$index])) {
			return $this->post[$index];
		}
		return null;
	}

	public function request($index) {
		if(isset($this->request[$index])) {
			return $this->request[$index];
		}
		return null;
	}

	public function cookie($index) {
		if(isset($this->cookie[$index])) {
			return $this->cookie[$index];
		}
		return null;
	}

	public function files($index) {
		if(isset($this->files[$index])) {
			return $this->files[$index];
		}
		return null;
	}

	public function server($index) {
		if(isset($this->server[$index])) {
			return $this->server[$index];
		}
		return null;
	}

  	public function clean($data) {
    	if (is_array($data)) {
	  		foreach ($data as $key => $value) {
				unset($data[$key]);

	    		$data[$this->clean($key)] = $this->clean($value);
	  		}
		} else {
	  		$data = htmlspecialchars($data, ENT_COMPAT);
		}

		return $data;
	}
}
