<?php
namespace Opencart\Admin\Controller\Common;
/**
 * Class Login
 *
 * Can be loaded using $this->load->controller('common/login');
 *
 * @package Opencart\Admin\Controller\Common
 */
class Login extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('common/login');

		$this->document->setTitle($this->language->get('heading_title'));

		// Check to see if user is already logged
		if ($this->user->isLogged() && isset($this->request->get['user_token']) && isset($this->session->data['user_token']) && ($this->request->get['user_token'] == $this->session->data['user_token'])) {
			$this->response->redirect($this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true));
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
		$this->session->data['login_token'] = oc_token(32);

		$data['login'] = $this->url->link('common/login.login', 'login_token=' . $this->session->data['login_token'], true);

		if ($this->config->get('config_mail_engine')) {
			$data['forgotten'] = $this->url->link('common/forgotten');
		} else {
			$data['forgotten'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/login', $data));
	}

	/**
	 * Login
	 *
	 * @return void
	 */
	public function login(): void {
		$this->load->language('common/login');

		$json = [];

		// Stop any undefined index messages.
		$required = [
			'username' => '',
			'password' => '',
			'redirect' => ''
		];

		$post_info = $this->request->post + $required;

		if ($this->user->isLogged() && isset($this->request->get['user_token']) && isset($this->session->data['user_token']) && ($this->request->get['user_token'] == $this->session->data['user_token'])) {
			$json['redirect'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true);
		}

		if (!isset($this->request->get['login_token']) || !isset($this->session->data['login_token']) || $this->request->get['login_token'] != $this->session->data['login_token']) {
			$this->session->data['error'] = $this->language->get('error_login');

			$json['redirect'] = $this->url->link('common/login', '', true);
		}

		if (!$json && !$this->user->login($post_info['username'], html_entity_decode($post_info['password'], ENT_QUOTES, 'UTF-8'))) {
			$json['error'] = $this->language->get('error_login');
		}

		if (!$json) {
			$this->session->data['user_token'] = oc_token(32);

			// Remove login token so it cannot be used again.
			unset($this->session->data['login_token']);

			$login_data = [
				'ip'         => oc_get_ip(),
				'user_agent' => $this->request->server['HTTP_USER_AGENT']
			];

			// User
			$this->load->model('user/user');

			$this->model_user_user->addLogin($this->user->getId(), $login_data);

			$json['redirect'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
