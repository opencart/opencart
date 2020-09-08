<?php
namespace Opencart\Application\Controller\Startup;
class Permission extends \Opencart\System\Engine\Controller {
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

			if ($part[0] == 'extension' && isset($part[2]) && isset($part[3])) {
				$route .= '/' . $part[2];
				$route .= '/' . $part[3];
			}

			// We want to ingore some pages from having its permission checked.
			$ignore = [
				'common/dashboard',
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'common/cron',
				'error/not_found',
				'error/permission'
			];

			if (!in_array($route, $ignore) && !$this->user->hasPermission('access', $route)) {
				return new \Opencart\System\Engine\Action('error/permission');
			}
		}
	}
}
