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

		// Remove any method call for checking ignore pages.
		$pos = strrpos($route, '.');

		if ($pos !== false) {
			$route = substr($route, 0, $pos);
		}

		$ignore = [
			'common/login',
			'common/logout',
			'common/forgotten',
			'common/authorize'
		];

		if ($this->config->get('config_security') && !in_array($route, $ignore)) {
			$this->load->model('user/user');

			$token_info = $this->model_user_user->getAuthorizeByToken($this->user->getId(), $token);

			if (!$token_info || !$token_info['status'] && $token_info['attempts'] <= 2) {
				return new \Opencart\System\Engine\Action('common/authorize');
			}

			if ($token_info && !$token_info['status'] && $token_info['attempts'] > 2) {
				return new \Opencart\System\Engine\Action('common/authorize.unlock');
			}
		}

		return null;
	}
}