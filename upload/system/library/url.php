<?php
class Url {
	private $host;
	private $rewrite = array();

	public function __construct($host) {
		$this->host = $host;
	}

	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}

	public function link($route, $args = '', $secure = false) {
		if (!$secure) {
			$url = 'http://' . $this->host;
		} else {
			$url = 'https://' . $this->host;
		}

		$url .= 'index.php?route=' . $route;

		if ($args) {
			$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
		}

		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}

		return $url;
	}
}
?>