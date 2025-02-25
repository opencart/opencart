<?php
namespace Opencart\Catalog\Controller\Startup;
/**
 * Class Authorize
 *
 * @package Opencart\Catalog\Controller\Startup
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

		// Block access to 2fa if not active or logged in
		if ($route == 'account/authorize') {
			// 1. Make se the customer is logged in.
			if (!$this->customer->isLogged()) {
				$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language'), true));
			}

			// 2. Make sure 2fa is enabled.
			if (!$this->config->get('config_2fa')) {
				$this->response->redirect($this->url->link('account/account', 'language=' . $this->config->get('config_language'), true));
			}
		}

		if ($this->config->get('config_2fa') && $this->customer->isLogged()) {
			// Stop direct access when not logged in
			$this->load->model('account/customer');

			$token_info = $this->model_account_customer->getAuthorizeByToken($this->customer->getId(), $token);

			// If already logged in and token is valid, redirect to account page.
			if ($token_info && $token_info['status']) {
				$this->response->redirect($this->url->link('account/account', 'language=' . $this->config->get('config_language') . 'customer_token=' . $this->sesssion->data['customer_token'], true));
			}

			$ignore = [
				'account/authorize',
				'account/logout'
			];

			if (!in_array($route, $ignore)) {
				if ($token_info && !$token_info['status'] && $token_info['attempts'] > 2) {
					return new \Opencart\System\Engine\Action('account/authorize.unlock');
				}

				if (!$token_info || !$token_info['status'] && $token_info['attempts'] <= 2) {
					return new \Opencart\System\Engine\Action('account/authorize');
				}
			}
		}

		return null;
	}
}
