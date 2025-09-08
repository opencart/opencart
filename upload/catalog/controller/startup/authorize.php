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
	public function index() {
		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = '';
		}

		if (isset($this->request->cookie['customer_authorize'])) {
			$token = (string)$this->request->cookie['customer_authorize'];
		} else {
			$token = '';
		}

		// Remove any method call for checking ignore pages.
		$pos = strrpos($route, '.');

		if ($pos !== false) {
			$route = substr($route, 0, $pos);
		}

		// Block access to 2fa, if logged in
		if ($route == 'account/authorize' && !$this->config->get('config_2fa')) {
			$this->response->redirect($this->url->link('common/home', 'language=' . $this->config->get('config_language'), true));
		}

		if ($this->config->get('config_2fa') && $this->customer->isLogged()) {
			// If already logged in and token is valid, redirect to account page to stop direct access.
			$this->load->model('account/customer');

			$token_info = $this->model_account_customer->getAuthorizeByToken($this->customer->getId(), $token);

			if ($token_info && $token_info['status']) {
				return null;
			}

			// Don't force redirect to authorize page if already on authorize page.
			$ignore = [
				'account/authorize',
				'account/logout'
			];

			if (!in_array($route, $ignore)) {
				if ($token_info && !$token_info['status'] && $token_info['total'] > 2) {
					return new \Opencart\System\Engine\Action('account/authorize.reset');
				}

				if (!$token_info || !$token_info['status'] && $token_info['total'] <= 2) {
					return new \Opencart\System\Engine\Action('account/authorize');
				}
			}
		}

		return null;
	}
}
