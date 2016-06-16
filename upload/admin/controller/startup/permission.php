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

			if (isset($part[2]) && (substr($this->request->get['route'], 0, 10) == 'extension/')) {
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
