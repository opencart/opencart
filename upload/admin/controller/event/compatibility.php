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
		//echo  $route;
		if (isset($data['back'])) {
			
		}
		
		/*
		// The below code will old extensions compatible with the extension page move 
		if (isset($this->request->get['route']) && ($this->request->get['route'] != 'extension/extension')) {
			$url_info = parse_url(str_replace('&amp;', '&', $link));
			
			$data = array();

			parse_str($url_info['query'], $data);
		
			
		
			$routes = array(
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
			
			if (in_array($part[0] . '/' . $part[1], $routes)) {
				$query  = '?route=extension/extension&type=' . $part[1];
				
				unset($data['route']);
				
				$query .= '&' . http_build_query($data);
			
				return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . $url_info['path'] . $query;
			} else {
				return $link;
			}
		} else {
			return $link;
		}
		*/
	}
}
