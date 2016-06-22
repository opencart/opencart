<?php
class ControllerEventCompatibility extends Controller {
	public function controller(&$route) {
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);



	}
	
	public function language(&$route) {
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		
		// Compatibility code for old extension folders
		$part = explode('/', $route);
		
		$extension = array(
			'extension/analytics',
			'extension/captcha',
			'extension/feed',
			'extension/fraud',
			'extension/module',
			'extension/payment',
			'extension/shipping',
			'extension/theme',
			'extension/total'
		);
		
		if ((count($part) >= 3) && in_array($part[0] . '/' . $part[1], $extension) && !is_file(DIR_LANGUAGE . $this->config->get('config_language') . '/' . $route . '.php') && is_file(DIR_LANGUAGE . $this->config->get('config_language') . '/' . $part[1] . '/' . $part[2] . '.php')) {
			$route = $part[1] . '/' . $part[2];
		}
	}
}
