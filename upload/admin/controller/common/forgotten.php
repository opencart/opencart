<?php
namespace Opencart\Admin\Controller\Common;
/**
 * Class Forgotten
 *
 * Can be loaded using $this->load->controller('common/forgotten');
 *
 * @package Opencart\Admin\Controller\Common
 */
class Forgotten extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('common/forgotten');

		if ($this->user->isLogged() || !$this->config->get('config_mail_engine')) {
			$this->response->redirect($this->url->link('common/login', '', true));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/forgotten')
		];

		$data['confirm'] = $this->url->link('common/forgotten.confirm');
		$data['back'] = $this->url->link('common/login');

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/forgotten', $data));
	}

	/**
	 * Confirm
	 *
	 * @return void
	 */
	public function confirm(): void {
		$this->load->language('common/forgotten');

		$json = [];

		// Stop any undefined index messages.
		if ($this->user->isLogged() || !$this->config->get('config_mail_engine')) {
			$json['redirect'] = $this->url->link('common/login', '', true);
		}

		if (!$json) {
			$post_info = ['email' => ''] + $this->request->post;

			// User
			$this->load->model('user/user');

			$user_info = $this->model_user_user->getUserByEmail((string)$post_info['email']);

			if (!$user_info) {
				$json['error'] = $this->language->get('error_email');
			}
		}

		if (!$json) {
			$this->session->data['success'] = $this->language->get('text_success');

			$this->model_user_user->addToken($user_info['customer_id'], 'password', oc_token(40));

			$json['redirect'] = $this->url->link('common/login', '', true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Reset
	 *
	 * @return void
	 */
	public function reset(): void {
		$this->load->language('common/forgotten');

		if (isset($this->request->get['email'])) {
			$email = (string)$this->request->get['email'];
		} else {
			$email = '';
		}

		if (isset($this->request->get['code'])) {
			$code = (string)$this->request->get['code'];
		} else {
			$code = '';
		}

		if ($this->user->isLogged() || !$this->config->get('config_mail_engine')) {
			$this->response->redirect($this->url->link('common/login', '', true));
		}

		// User
		$this->load->model('user/user');

		$user_info = $this->model_user_user->getTokenByCode($code);

		if (!$user_info || !$user_info['email'] || $user_info['email'] !== $email || $user_info['type'] != 'password') {
			$this->model_account_customer->deleteTokenByCode($code);

			$this->session->data['error'] = $this->language->get('error_code');

			$this->response->redirect($this->url->link('common/login', '', true));
		}

		$this->document->setTitle($this->language->get('heading_reset'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/forgotten.reset')
		];

		$this->session->data['reset_token'] = oc_token(26);

		$data['reset'] = $this->url->link('common/forgotten.password', 'email=' . urlencode($email) . '&code=' . $code . '&reset_token=' . $this->session->data['reset_token']);
		$data['back'] = $this->url->link('common/login');

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/forgotten_reset', $data));
	}

	/**
	 * Password
	 *
	 * @return void
	 */
	public function password(): void {
		$this->load->language('common/forgotten');

		$json = [];

		if (isset($this->request->get['email'])) {
			$email = urldecode((string)$this->request->get['email']);
		} else {
			$email = '';
		}

		if (isset($this->request->get['code'])) {
			$code = (string)$this->request->get['code'];
		} else {
			$code = '';
		}

		if ($this->user->isLogged() || !$this->config->get('config_mail_engine')) {
			$this->response->redirect($this->url->link('common/login', '', true));
		}

		if (!isset($this->request->get['reset_token']) || !isset($this->session->data['reset_token']) || ($this->session->data['reset_token'] != $this->request->get['reset_token'])) {
			$this->session->data['error'] = $this->language->get('error_session');

			$json['redirect'] = $this->url->link('account/forgotten', '', true);
		}

		// User
		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUserByEmail($email);

		if (!$user_info || !$user_info['email'] || $user_info['email'] !== $email || $user_info['type'] != 'password') {
			// Reset token
			$this->model_account_customer->deleteTokenByCode($code);

			$this->session->data['error'] = $this->language->get('error_code');

			$json['redirect'] = $this->url->link('common/login', '', true);
		}

		if (!$json) {
			$post_info = $this->request->post;

			$password = html_entity_decode((string)$post_info['password'], ENT_QUOTES, 'UTF-8');

			if (!oc_validate_length($password, (int)$this->config->get('config_user_password_length'), 40)) {
				$json['error']['password'] = sprintf($this->language->get('error_password_length'), (int)$this->config->get('config_user_password_length'));
			}

			$required = [];

			if ($this->config->get('config_user_password_uppercase') && !preg_match('/[A-Z]/', $password)) {
				$required[] = $this->language->get('error_password_uppercase');
			}

			if ($this->config->get('config_user_password_lowercase') && !preg_match('/[a-z]/', $password)) {
				$required[] = $this->language->get('error_password_lowercase');
			}

			if ($this->config->get('config_user_password_number') && !preg_match('/[0-9]/', $password)) {
				$required[] = $this->language->get('error_password_number');
			}

			if ($this->config->get('config_user_password_symbol') && !preg_match('/[^a-zA-Z0-9]/', $password)) {
				$required[] = $this->language->get('error_password_symbol');
			}

			if ($required) {
				$json['error']['password'] = sprintf($this->language->get('error_password'), implode(', ', $required), (int)$this->config->get('config_user_password_length'));
			}

			if ($post_info['confirm'] != $post_info['password']) {
				$json['error']['confirm'] = $this->language->get('error_confirm');
			}
		}

		if (!$json) {
			$this->session->data['success'] = $this->language->get('text_reset');

			$this->model_user_user->editPassword($user_info['user_id'], $post_info['password']);

			// Remove for token
			unset($this->session->data['reset_token']);

			$this->model_account_customer->deleteTokenByCode($code);

			$json['redirect'] = $this->url->link('common/login', '', true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
