<?php
class ControllerEventCompatibility extends Controller {
	public function controller(&$route) {
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		
		// Compatibility code for old extension folders
		$part = explode('/', $route);
				
		if (!is_file(DIR_APPLICATION . 'controller/' . $route . '.php') && is_file(DIR_APPLICATION . 'controller/' . $part[1] . '/' . $part[2] . '.php')) {
			$route = $part[1] . '/' . $part[2];
		}
	}
	
	public function language(&$route) {
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		
		// Compatibility code for old extension folders
		$part = explode('/', $route);
		
		if (!is_file(DIR_LANGUAGE . $this->config->get('config_language') . '/' . $route . '.php') && is_file(DIR_LANGUAGE . $this->config->get('config_language') . '/' . $part[1] . '/' . $part[2] . '.php')) {
			$route = $part[1] . '/' . $part[2];
		}
	}
	
	public function view(&$route, &$data) {
		$part = explode('/', $route);
			
		if (isset($part[0]) && isset($data['back'])) {
			$data['back'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=' . $part[0], true);
		}
	}
}
