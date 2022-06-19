<?php
namespace Opencart\Admin\Controller\Startup;
class Authorize extends \Opencart\System\Engine\Controller {
	public function index(): object|null {
		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = '';
		}

		if (isset($this->request->cookie['authorize'])) {
			$token = (string)$this->request->cookie['authorize'];
		} else {
			$token = '';
		}

		// $this->config->get('config_security') &&
		if ($route != 'common/login') {
			$token_info = $this->model_user_user->getLoginByToken($this->user->getId(), $token);

			if (!$token_info) {
				return new \Opencart\System\Engine\Action('common/authorize');
			}

			if ($token_info && $token_info['total'] >= 3) {
				//return new \Opencart\System\Engine\Action('error/authorize|permission');
			}
		}

		return null;
	}
}
