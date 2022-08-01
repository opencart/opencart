<?php
namespace Opencart\Admin\Controller\Common;
use \Opencart\System\Helper as Helper;
class Login extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('common/login');

		$this->document->setTitle($this->language->get('heading_title'));

		// Check to see if user is already logged
		if ($this->user->isLogged() && isset($this->request->get['user_token']) && isset($this->session->data['user_token']) && ($this->request->get['user_token'] == $this->session->data['user_token'])) {
			$this->response->redirect($this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']));
		}

		// Check to see if user is using incorrect token
		if (isset($this->request->get['user_token']) && (!isset($this->session->data['user_token']) || ($this->request->get['user_token'] != $this->session->data['user_token']))) {
			$data['error_warning'] = $this->language->get('error_token');
		} elseif (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		// Create a login token to prevent brute force attacks
		$this->session->data['login_token'] = Helper\General\token(32);

		$data['login'] = $this->url->link('common/login|login', 'login_token=' . $this->session->data['login_token'], true);

		$data['forgotten'] = $this->url->link('common/forgotten');

		if (isset($this->request->get['route']) && $this->request->get['route'] != 'common/login') {
			$args = $this->request->get;

			$route = $args['route'];

			unset($args['route']);
			unset($args['user_token']);

			$url = '';

			if ($this->request->get) {
				$url .= http_build_query($args);
			}

			$data['redirect'] = $this->url->link($route, $url);
		} else {
			$data['redirect'] = '';
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
			$json['redirect'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true);
		}

		if (!isset($this->request->get['login_token']) || !isset($this->session->data['login_token']) || $this->request->get['login_token'] != $this->session->data['login_token']) {
			$this->session->data['error'] = $this->language->get('error_login');

			$json['redirect'] = $this->url->link('common/login', '', true);
		}

		if (!$json && !$this->user->login($this->request->post['username'], html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8'))) {
			$json['error'] = $this->language->get('error_login');
		}

		if (!$json) {
			$this->session->data['user_token'] = Helper\General\token(32);

			// Remove login token so it cannot be used again.
			unset($this->session->data['login_token']);

			if ($this->request->post['redirect'] && (strpos($this->request->post['redirect'], HTTP_SERVER) === 0)) {
				$json['redirect'] = str_replace('&amp;', '&',  $this->request->post['redirect'] . '&user_token=' . $this->session->data['user_token']);
			} else {
				$json['redirect'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
