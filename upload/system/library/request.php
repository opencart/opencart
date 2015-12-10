<?php
class Request {
	public $get = array();
	public $post = array();
	public $cookie = array();
	public $files = array();
	public $server = array();

	public function __construct() {
		$this->get = $this->clean($_GET);
		$this->post = $this->clean($_POST);
		$this->request = $this->clean($_REQUEST);
		$this->cookie = $this->clean($_COOKIE);
		$this->files = $this->clean($_FILES);
		$this->server = $this->clean($_SERVER);
	}

	public function clean($data) {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				unset($data[$key]);

				$data[$this->clean($key)] = $this->clean($value);
			}
		} else {
			$data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
		}

		return $data;
	}

	public function post($key, $default = null) {
		return isset($this->post[$key]) ? $this->post[$key] : $default;
	}

	public function get($key, $default = null) {
		return isset($this->get[$key]) ? $this->get[$key] : $default;
	}

	public function request($key, $default = null) {
		return isset($this->request[$key]) ? $this->request[$key] : $default;
	}

	public function cookie($key, $default = null) {
		return isset($this->cookie[$key]) ? $this->cookie[$key] : $default;
	}

	public function files($key, $default = null) {
		return isset($this->files[$key]) ? $this->files[$key] : $default;
	}

	public function server($key, $default = null) {
		return isset($this->server[$key]) ? $this->server[$key] : $default;
	}
}
