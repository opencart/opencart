<?php
class Request {
	public $get = array();
	public $post = array();
	public $cookie = array();
	public $files = array();
	public $server = array();

	public function __construct() {
		$this->get = new Bag($this->clean($_GET));
		$this->post = new Bag($this->clean($_POST));
		$this->request = new Bag($this->clean($_REQUEST));
		$this->cookie = new Bag($this->clean($_COOKIE));
		$this->files = new Bag($this->clean($_FILES));
		$this->server = new Bag($this->clean($_SERVER));
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
}