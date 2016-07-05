<?php
class ControllerStartupCompatibility extends Controller {
	public function index() {
		if (isset($this->request->get['route'])) {
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
		
			$part = explode('/', $this->request->get['route']);
			
			if (isset($part[0]) && isset($part[1]) && in_array($part[0] . '/' . $part[1], $extension)) {
				$route = '';
				
				if (isset($part[2])) {
					$route = '/' . $part[2];
				}
				
				$this->response->redirect('extension/extension' . $route, 'token=' . $this->session->data['token'], true);	
			}
		}
	}
}
