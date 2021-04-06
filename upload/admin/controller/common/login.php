<?php
namespace Opencart\Admin\Controller\Common;
class Login extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('common/login');

		$this->document->setTitle($this->language->get('heading_title'));

		// Check to see if user is already logged
		if ($this->user->isLogged() && isset($this->request->get['user_token']) && isset($this->session->data['user_token']) && ($this->request->get['user_token'] == $this->session->data['user_token'])) {
			$this->response->redirect($this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']));
		}

		// Check to see if user is using incorrect token
		if (isset($this->request->get['user_token']) && !isset($this->session->data['user_token']) && ($this->request->get['user_token'] != $this->session->data['user_token'])) {
			$data['warning_error'] = $this->language->get('error_token');

			unset($this->session->data['user_token']);
		} else {
			$data['warning_error'] = '';
		}

		// Display any success messages
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->get['route']) && $this->request->get['route'] != 'common/login') {
			$route = $this->request->get['route'];

			unset($this->request->get['route']);
			unset($this->request->get['user_token']);

			$url = '';

			if ($this->request->get) {
				$url .= http_build_query($this->request->get);
			}

			$data['redirect'] = $this->url->link($route, $url);
		} else {
			$data['redirect'] = '';
		}

		if ($this->config->get('config_password')) {
			$data['forgotten'] = $this->url->link('common/forgotten');
		} else {
			$data['forgotten'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/login', $data));
	}

	public function login(): void {
		$this->load->language('common/login');

		$json = [];

		// Stop any undefined index messages.
		$keys = [
			'username',
			'password',
			'redirect'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		if ($this->user->isLogged() && isset($this->request->get['user_token']) && isset($this->session->data['user_token']) && ($this->request->get['user_token'] == $this->session->data['user_token'])) {
			$json['redirect'] = $this->request->post['redirect'] . '&user_token=' . $this->session->data['user_token'];
		}

		if (!$this->user->login($this->request->post['username'], html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8'))) {
			$json['error'] = $this->language->get('error_login');
		}

		if (!$json) {
			$this->session->data['user_token'] = token(32);

			if ($this->request->post['redirect'] && (strpos($this->request->post['redirect'], HTTP_SERVER) === 0)) {
				$json['redirect'] = $this->request->post['redirect'] . '&user_token=' . $this->session->data['user_token'];
			} else {
				$json['redirect'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
