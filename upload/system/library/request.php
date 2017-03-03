<?php
class Request {
	public $get = array();
	public $post = array();
	public $cookie = array();
	public $files = array();
	public $server = array();
	
	protected $is_get;
	protected $is_post;
	protected $is_ssl;
	protected $is_ajax;

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
		if ($this->is_get === null) {
			$this->is_get = ($this->server['REQUEST_METHOD'] === 'GET');
		}

		return $this->is_get;
	}

	public function isPost() {
		if ($this->is_post === null) {
			$this->is_post = ($this->server['REQUEST_METHOD'] === 'POST');
		}

		return $this->is_post;
	}

	public function isSSL() {
		if ($this->is_ssl === null) {
			$https = strtolower($this->server['HTTPS']);
			$this->is_ssl = ($https === 'on' || $https === '1');
		}

		return $this->is_ssl;
	}

	public function isSecure() {
		return $this->isSSL();
	}

	public function isAjax() {
		if ($this->is_ajax === null) {
			$hxrw = strtolower($this->server['HTTP_X_REQUESTED_WITH']);
			$this->is_ajax = ($hxrw === 'xmlhttprequest');
		}

		return $this->is_ajax;
	}
}
