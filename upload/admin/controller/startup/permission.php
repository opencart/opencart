<?php
class ControllerStartupPermission extends Controller {
	public function index() {
		if (isset($this->request->get['route'])) {
			$route = '';
			
			$part = explode('/', $this->request->get['route']);

			if (isset($part[0])) {
				$route .= $part[0];
			}

			if (isset($part[1])) {
				$route .= '/' . $part[1];
			}

			// If a 3rd part is found we need to check if its under one of the extension folders.
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
			
			if (isset($part[2]) && (in_array(substr($this->request->get['route'], 0, 10), $routes))) {
				$route .= '/' . $part[2];
			}

			$ignore = array(
				'common/dashboard',
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission',
				'dashboard/order',
				'dashboard/sale',
				'dashboard/customer',
				'dashboard/online',
				'dashboard/map',
				'dashboard/activity',
				'dashboard/chart',
				'dashboard/recent'
			);

			if (!in_array($route, $ignore) && !$this->user->hasPermission('access', $route)) {
				return new Action('error/permission');
			}
		}
	}
}
