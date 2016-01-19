<?php
class Url {
	private $base;
	private $ssl;
	private $rewrite = array();

	public function __construct($base, $ssl = false) {
		$this->base = $base;
		$this->ssl = $ssl;
	}
	
	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}

	public function link($route, $args = '', $secure = false) {
		if ($secure) {
			return $this->ssl($route, $args);
		}

		$url = 'http://' . $this->base . 'index.php?route=' . $route;

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
		if ($this->ssl) {
			$url = 'https://' . $this->base . 'index.php?route=' . $route;
		} else {
			$url = 'http://' . $this->base . 'index.php?route=' . $route;
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
