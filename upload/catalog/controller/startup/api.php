<?php
namespace Opencart\Catalog\Controller\Startup;
/**
 * Class Api
 *
 * @package Opencart\Catalog\Controller\Startup
 */
class Api extends \Opencart\System\Engine\Controller {
	/**
	 * @return object|\Opencart\System\Engine\Action|null
	 */
	public function index(): object|null {
		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = '';
		}

		if (substr($route, 0, 4) == 'api/' && $route !== 'api/account/login' && !isset($this->session->data['api_id'])) {
			return new \Opencart\System\Engine\Action('error/permission');
		}

		return null;
	}
}
