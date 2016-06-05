<?php
class ControllerStartupCompatibility extends Controller {
	public function index() {
		// Route
		if (isset($this->request->get['route']) && $this->user->isLogged()) {
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
			
			foreach ($routes as $route) {
				if (substr($this->request->get['route'], 0, strlen($route)) == $route) {
					$method = substr($this->request->get['route'], strlen($route));
					
					if (!$method) {
						return new Action('extension/extension');
					} else {
						return new Action('extension/extension/' . $method);
					}
				}
			}
		}
	}
}
