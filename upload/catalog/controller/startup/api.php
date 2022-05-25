<?php
namespace Opencart\Catalog\Controller\Startup;
class Api extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/login');

		$json = [];

		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = '';
		}

		if (substr($route, 0, 4) == 'api/') {
			// Remove any method call for checking ignore pages.
			$pos = strrpos($route, '|');

			if ($pos !== false) {
				$route = substr($this->request->get['route'], 0, $pos);
			}

			if ($route !== 'api/login' && !isset($this->session->data['api_id'])) {
				$json['error'] = $this->language->get('error_permission');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
