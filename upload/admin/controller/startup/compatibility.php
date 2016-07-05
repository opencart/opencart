<?php
class ControllerStartupCompatibility extends Controller {
	public function index() {
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
					
		
		if (in_array()) {
			
		}
		
		
		// Adding a rewrite so any link to the extension page is changed to to the new extension system  
		$this->url->addRewrite($this);
	}
	
	public function	rewrite($link) {
		// The below code will old extensions compatible with the extension page move 
		if (isset($this->request->get['route']) && ($this->request->get['route'] != 'extension/extension')) {
			$url_info = parse_url(str_replace('&amp;', '&', $link));
			
			$data = array();

			parse_str($url_info['query'], $data);
		
			$part = explode('/', $data['route']);	
		
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
	}
}
