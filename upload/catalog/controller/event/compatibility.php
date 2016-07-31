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
		
		if (!is_file(DIR_APPLICATION . 'controller/' . $route . '.php') && is_file(DIR_APPLICATION . 'controller/' . $part[1] . '/' . $part[2] . '.php')) {
			$route = $part[1] . '/' . $part[2];
		}
	}
	
	public function beforeModel(&$route) {
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		
		$part = explode('/', $route);

		if (!is_file(DIR_APPLICATION . 'model/' . $route . '.php') && is_file(DIR_APPLICATION . 'model/' . $part[1] . '/' . $part[2] . '.php')) {
			$route = $part[1] . '/' . $part[2];
		}
	}
	
	public function afterModel(&$route) {
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		
		$part = explode('/', $route);
					
		$this->{'model_extension_' . $part[0] . '_' . $part[1]} = $this->{'model_' . $part[0] . '_' . $part[1]};
	}
		
	public function language(&$route) {
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
				
		$part = explode('/', $route);	
				
		if (!is_file(DIR_LANGUAGE . $this->config->get('config_language') . '/' . $route . '.php') && is_file(DIR_LANGUAGE . $this->config->get('config_language') . '/' . $part[1] . '/' . $part[2] . '.php')) {
			$route = $part[1] . '/' . $part[2];
		}
	}		
}