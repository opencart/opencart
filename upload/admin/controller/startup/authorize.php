<?php
namespace Opencart\Admin\Controller\Startup;
/**
 * Class Authorize
 *
 * @package Opencart\Admin\Controller\Startup
 */
class Authorize extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return \Opencart\System\Engine\Action|null
	 */
	public function index(): ?\Opencart\System\Engine\Action {
		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = '';
		}

		if (isset($this->request->cookie['admin_authorize'])) {
			$token = (string)$this->request->cookie['admin_authorize'];
		} else {
			$token = '';
		}

		// Remove any method call for checking ignore pages.
		$pos = strrpos($route, '.');

		if ($pos !== false) {
			$route = substr($route, 0, $pos);
		}

		// Block access to 2fa, if not active or logged in
		if ($route == 'common/authorize' && !$this->config->get('config_user_2fa')) {
			$this->response->redirect($this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true));
		}

		if ($this->config->get('config_user_2fa')) {
			// If already logged in and token is valid, redirect to account page to stop direct access.
			$this->load->model('user/user');

			$token_info = $this->model_user_user->getAuthorizeByToken($this->user->getId(), $token);

			if ($token_info && $token_info['status']) {
				return null;
			}

			// Don't force redirect to authorize page if already on authorize page.
			$ignore = [
				'common/authorize',
				'common/login',
				'common/logout',
				'common/forgotten'
			];

			if (!in_array($route, $ignore)) {
				if ($token_info && !$token_info['status'] && $token_info['attempts'] > 2) {
					return new \Opencart\System\Engine\Action('common/authorize.reset');
				}

				if (!$token_info || !$token_info['status'] && $token_info['attempts'] <= 2) {
					return new \Opencart\System\Engine\Action('common/authorize');
				}
			}
		}

		return null;
	}
}
