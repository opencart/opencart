<?php
namespace Opencart\Catalog\Controller\Startup;
class Login extends \Opencart\System\Engine\Controller {
	public function index(): void {
		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = '';
		}

		if (substr($route, 0, 7) == 'account') {
			// Remove any method call for checking ignore pages.
			$pos = strrpos($route, '|');

			if ($pos !== false) {
				$route = substr($this->request->get['route'], 0, $pos);
			}

			$ignore = [
				'account/login',
				'account/logout',
				'account/register',
				'account/success',
				'account/forgotten'
			];

			if (!in_array($route, $ignore) && (!isset($this->request->get['customer_token']) || !isset($this->session->data['customer_token']) || ($this->request->get['customer_token'] != $this->session->data['customer_token']))) {
				//$this->session->data['error'] = $this->url->link($this->request->get['route'], 'language=' . $this->config->get('config_language'));

				$url_data = $this->request->get;

				if (isset($url_data['route'])) {
					$route = $url_data['route'];
				} else {
					$route = $this->config->get('action_default');
				}

				unset($url_data['route']);
				unset($url_data['_route_']);

				$url = '';

				if ($url_data) {
					$url = '&' . urldecode(http_build_query($url_data));
				}

				//$this->session->data['redirect'] = $this->url->link($this->request->get['route'], $url);

				//$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
			}
		}
	}
}
