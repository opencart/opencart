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

	public function isGet() {
		static $is_get = null;
		if ($is_get === null) {
			$is_get = ($this->server['REQUEST_METHOD'] === 'GET');
		}
		return $is_get;
	}

	public function isPost() {
		static $is_post = null;
		if ($is_post === null) {
			$is_post = ($this->server['REQUEST_METHOD'] === 'POST');
		}
		return $is_post;
	}

	public function isSecure() {
		static $is_secure = null;
		if ($is_secure === null) {
			$https = $this->server['HTTPS'];
			$is_secure = ($https === 'on' || $https === '1');
		}
		return $is_secure;
	}

	public function isSSL() {
		return $this->isSecure();
	}

	public function isAjax() {
		static $is_ajax = null;
		if ($is_ajax === null) {
			$hxrw = $this->server['HTTP_X_REQUESTED_WITH'];
			$is_ajax = (strtolower($hxrw) === 'xmlhttprequest');
		}
		return $is_ajax;
	}
}
