<?php
namespace Opencart\Catalog\Controller\Startup;
/**
 * Class Api
 *
 * @package Opencart\Catalog\Controller\Startup
 */
class Api extends \Opencart\System\Engine\Controller {
	/**
	 * @return \Opencart\System\Engine\Action|null
	 */
	public function index(): ?\Opencart\System\Engine\Action {
		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = '';
		}

		if (substr($route, 0, 4) == 'api/' && $route !== 'api/account/login' && !isset($this->session->data['api_id'])) {
			return new \Opencart\System\Engine\Action('error/permission');
		}

		if (isset($this->request->get['store_id'])) {
			$store_id = (int)$this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		if (isset($this->request->get['language'])) {
			$language = $this->request->get['language'];
		} else {
			$language = $this->config->get('config_language');
		}

		return null;
	}
}
