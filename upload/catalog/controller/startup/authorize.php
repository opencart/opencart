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

		$ignore = [
			'account/authorize',
			'account/logout'
		];

		if ($this->config->get('config_2fa') && $this->customer->isLogged() && !in_array($route, $ignore)) {
			$this->load->model('account/customer');

			$token_info = $this->model_account_customer->getAuthorizeByToken($this->customer->getId(), $token);

			if ($token_info && !$token_info['status'] && $token_info['attempts'] > 2) {
				return new \Opencart\System\Engine\Action('account/authorize.unlock');
			}

			if (!$token_info || !$token_info['status'] && $token_info['attempts'] <= 2) {
				return new \Opencart\System\Engine\Action('account/authorize');
			}
		}

		return null;
	}
}
