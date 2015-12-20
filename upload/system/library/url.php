<?php
class Url {
	private $domain;
	private $rewrite = array();

	public function __construct($domain) {
		$this->domain = $domain;
	}
	
	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}

	public function link($route, $args = '', $secure = false) {
		if (!$secure) {
			return $this->ssl($route, $args);
		}

		$url = 'http://' . $this->domain . '/index.php?route=' . $route;

		if ($args) {
			if (is_array($args)) {
				$url .= '&amp;' . http_build_query($args);
			} else {
				$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
			}
		}

		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}

		return $url;
	}
	
	public function ssl($route, $args = '') {
		if ($_SERVER['HTTPS']) {
			$url = 'https://' . $this->domain . '/index.php?route=' . $route;
		} else {
			$url = 'http://' . $this->domain . '/index.php?route=' . $route;
		}
		
		if ($args) {
			if (is_array($args)) {
				$url .= '&amp;' . http_build_query($args);
			} else {
				$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
			}
		}

		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}

		return $url;		
	}
}