<?php
class ControllerEventCompatibility extends Controller {
	// The below code will old extensions compatible with the extension page move 
	/*
		Compatiblity for: 
		
		analytics
		captcha
		credit_card
		feed
		fraud
		module
		payment
		recurring
		shipping
		theme
		total
		openbay
	*/
	public function controller(&$route) {
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		
		$part = explode('/', $route);
		
		$extension = array(
			'extension/analytics',
			'extension/captcha',
			'extension/credit_card',
			'extension/feed',
			'extension/fraud',
			'extension/module',
			'extension/payment',
			'extension/recurring',
			'extension/shipping',
			'extension/theme',
			'extension/total'
		);
		
		if ((count($part) >= 3) && in_array($part[0] . '/' . $part[1], $extension) && !is_file(DIR_APPLICATION . 'controller/' . $this->config->get('config_language') . '/' . $route . '.php') && is_file(DIR_APPLICATION . 'controller/' . $this->config->get('config_language') . '/' . $part[1] . '/' . $part[2] . '.php')) {
			$route = $part[1] . '/' . $part[2];
		}
	}
	
	public function model(&$route) {
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		
		$part = explode('/', $route);
		
		$extension = array(
			'extension/analytics',
			'extension/captcha',
			'extension/credit_card',
			'extension/feed',
			'extension/fraud',
			'extension/module',
			'extension/payment',
			'extension/shipping',
			'extension/theme',
			'extension/total'
		);
		
		if ((count($part) >= 3) && in_array($part[0] . '/' . $part[1], $extension) && !is_file(DIR_APPLICATION . 'model/' . $this->config->get('config_language') . '/' . $route . '.php') && is_file(DIR_LANGUAGE . $this->config->get('config_language') . '/' . $part[1] . '/' . $part[2] . '.php')) {
			$route = $part[1] . '/' . $part[2];
		}
	}	
}