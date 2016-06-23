<?php
class ControllerEventCompatibility extends Controller {
	// 
	/*
	The below code will old extensions compatible with the extension page move 
	
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
		
		if ((count($part) > 2) && in_array($part[0] . '/' . $part[1], $extension) && !is_file(DIR_APPLICATION . 'controller/' . $route . '.php') && is_file(DIR_APPLICATION . 'controller/' . $this->config->get('config_language') . '/' . $part[1] . '/' . $part[2] . '.php')) {
			$route = $part[1] . '/' . $part[2];
		}
	}
	
	public function beforeModel(&$route) {
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		/*
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
		*/
		echo $route . 'fdgfg<br>';
		
		if (!is_file(DIR_APPLICATION . 'model/' . $route . '.php') && is_file(DIR_APPLICATION . 'model/' . $part[1] . '/' . $part[2] . '.php')) {
			echo $route . '<br>';
			
			$route = $part[1] . '/' . $part[2];
			
			echo $route . '<br>';
		}
	}
	
	public function afterModel(&$route) {
		echo $route . '<br>';
		
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		
		$part = explode('/', $route);
			
		$extension = array(
			'analytics',
			'captcha',
			'credit_card',
			'feed',
			'fraud',
			'module',
			'payment',
			'recurring',
			'shipping',
			'theme',
			'total'
		);
		//isset($part[0]) && isset($part[1]) && 
					
		if (in_array($part[0], $extension)) { 
			echo 'hi';
			$this->{'model_extension_' . $part[0] . '_' . $part[1]} = $this->{'model_' . $part[0] . '_' . $part[1]};
		}
	}
		
	public function language(&$route) {
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		
		// Compatibility code for old extension folders
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
		
		if ((count($part) >= 3) && in_array($part[0] . '/' . $part[1], $extension) && !is_file(DIR_LANGUAGE . $this->config->get('config_language') . '/' . $route . '.php') && is_file(DIR_LANGUAGE . $this->config->get('config_language') . '/' . $part[1] . '/' . $part[2] . '.php')) {
			$route = $part[1] . '/' . $part[2];
		}
	}		
}