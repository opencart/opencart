<?php
class ControllerStartupCompatibility extends Controller {
	public function index() {
		// Adding a rewrite so any link to the extension page is changed to to the new extension system  
		$this->url->addRewrite($this);
	}
	
	public function	rewrite($link) {
		$routes = array(
			'analytics',
			'captcha',
			'feed',
			'fraud',
			'extension/module',
			'extension/payment',
			'extension/shipping',
			'extension/theme',
			'extension/total'
		);		
		
		
		
		
		if (isset($this->request->get['route']) && (substr($this->request->get['route'], 10) == 'extension/') && ($this->request->get['route'] != 'extension/extension')) {
			$url_info = parse_url(str_replace('&amp;', '&', $link));
			
			echo 'hi';
			
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
			
			$data = array();

			parse_str($url_info['query'], $data);
		
			if (in_array($data['route'], $routes)) {
				$data['route'] = 'extension/extension';
				$data['type'] = substr($data['route'], 10);
				
				$query = http_build_query($data); 
			}
		
			return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . $url_info['path'] . $query;
		} else {
			return $link;
		}	
	}
}
