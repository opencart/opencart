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
			
			if (isset($part[2])) {
				// If a 3rd part is found we need to check if its under one of the extension folders.
				$routes = array(
					'extension/dashboard',
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
					$route .= '/' . $part[2];
				}
			}
		
			// We want to ingore some pages from having its permission checked. 
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
