<?php
class Url {
	private $url;
	private $ssl;
	private $rewrite = array();
	
	public function __construct($url, $ssl = '') {
		$this->url = $url;
		$this->ssl = $ssl;
	}
		
	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}
		
	public function link($route, $args = '', $connection = 'NONSSL') {
		if ($connection ==  'NONSSL') {
			$url = $this->url;
		} else {
			$url = $this->ssl;	
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

	function build($url) {
		if (function_exists('http_build_url')) {
			return http_build_url($url);
		}
		return implode('', array(
			'scheme'   => $url['scheme'] . '://',
			'userpass' => (empty($url['user']) || empty($url['pass']) ? '' : $url['user'] . ':' . $url['pass'] . '@'),
			'host'     => $url['host'],
			'port'     => (empty($url['port']) ? '' : ':' . $url['port']),
			'path'     => $url['path'],
			'query'    => (empty($url['query']) ? '' : '?' . $url['query']),
			'fragment' => (empty($url['fragment']) ? '' : '#' . $url['fragment']),
		));
	}

}
?>