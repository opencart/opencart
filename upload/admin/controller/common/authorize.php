<?php
namespace Opencart\Admin\Controller\Common;
/**
 * Class Authorize
 *
 * @package Opencart\Admin\Controller\Common
 */
class Authorize extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('common/authorize');

		if (isset($this->request->cookie['admin_authorize'])) {
			$token = $this->request->cookie['admin_authorize'];
		} else {
			$token = '';
		}

		$this->document->setTitle($this->language->get('heading_title'));

		// Check to see if user is using incorrect token
		if (isset($this->session->data['error'])) {
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

		$this->load->model('user/user');

		$login_info = $this->model_user_user->getAuthorizeByToken($this->user->getId(), $token);

		if (!$login_info) {
			// Create a token that can be stored as a cookie and will be used to identify device is safe.
			$token = oc_token(32);

			$authorize_data = [
				'token'      => $token,
				'ip'         => oc_get_ip(),
				'user_agent' => $this->request->server['HTTP_USER_AGENT']
			];

			$this->model_user_user->addAuthorize($this->user->getId(), $authorize_data);

			setcookie('authorize', $token, time() + 60 * 60 * 24 * 90);
		}

		$data['action'] = $this->url->link('common/authorize.validate', 'user_token=' . $this->session->data['user_token']);

		// Set the code to be emailed
		$this->session->data['code'] = oc_token(4);

		if ($this->request->get['route'] != 'common/login' && $this->request->get['route'] != 'common/authorize') {
			$args = $this->request->get;

			$route = $args['route'];

			unset($args['route']);
			unset($args['user_token']);

			$url = '';

			if ($args) {
				$url .= http_build_query($args);
			}

			$data['redirect'] = $this->url->link($route, $url);
		} else {
			$data['redirect'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true);
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/authorize', $data));
	}

	/**
	 * Send
	 *
	 * @return void
	 */
	public function send(): void {
		$this->load->language('common/authorize');

		$json = [];

		if (isset($this->request->cookie['admin_authorize'])) {
			$token = $this->request->cookie['admin_authorize'];
		} else {
			$token = '';
		}

		// 3. If token already exists check its valid
		$this->load->model('account/customer');

		$token_info = $this->model_account_customer->getAuthorizeByToken($this->customer->getId(), $token);

		if (!$token_info) {
			$json['redirect'] = $this->url->link('account/authorize', 'language=' . $this->config->get('config_language'), true);
			// If token is valid and total attempts are more than 2, redirect to unlock page.
		} elseif ($token_info['total'] > 2) {
			$json['redirect'] = $this->url->link('account/authorize.unlock', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			// Set the code to be emailed
			$this->session->data['code'] = oc_token(6);

			$json['success'] = $this->language->get('text_resend');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Validate
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('common/authorize');

		$json = [];

		if (isset($this->request->cookie['admin_authorize'])) {
			$token = $this->request->cookie['admin_authorize'];
		} else {
			$token = '';
		}

		$this->load->model('user/user');

		$authorize_info = $this->model_user_user->getAuthorizeByToken($this->user->getId(), $token);

		if ($authorize_info) {
			if (($authorize_info['attempts'] <= 2) && (!isset($this->request->post['code']) || !isset($this->session->data['code']) || ($this->request->post['code'] != $this->session->data['code']))) {
				$json['error'] = $this->language->get('error_code');

				$this->model_user_user->editAuthorizeTotal($authorize_info['user_authorize_id'], $authorize_info['total'] + 1);
			}

			if ($authorize_info['attempts'] >= 2) {
				$json['redirect'] = $this->url->link('common/authorize.unlock', 'user_token=' . $this->session->data['user_token'], true);
			}
		} else {
			$json['error'] = $this->language->get('error_code');
		}

		if (!$json) {
			$this->model_user_user->editAuthorizeStatus($authorize_info['user_authorize_id'], true);
			$this->model_user_user->editAuthorizeTotal($authorize_info['user_authorize_id'], 0);

			if (isset($this->request->post['redirect'])) {
				$redirect = urldecode(html_entity_decode($this->request->post['redirect'], ENT_QUOTES, 'UTF-8'));
			} else {
				$redirect = '';
			}

			// Register the cookie for security.
			if ($redirect && str_starts_with($redirect, HTTP_SERVER)) {
				$json['redirect'] = $redirect . '&user_token=' . $this->session->data['user_token'];
			} else {
				$json['redirect'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Unlock
	 *
	 * @return void
	 */
	public function unlock(): void {
		$this->load->language('common/authorize');

		if (isset($this->request->cookie['admin_authorize'])) {
			$token = $this->request->cookie['admin_authorize'];
		} else {
			$token = '';
		}

		$this->load->model('user/user');

		$authorize_info = $this->model_user_user->getAuthorizeByToken($this->user->getId(), $token);

		if ($authorize_info && $authorize_info['status']) {
			// Redirect if already have a valid token.
			$this->response->redirect($this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true));
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/authorize_unlock', $data));
	}

	/**
	 * Confirm
	 *
	 * @return void
	 */
	public function confirm(): void {
		$this->load->language('common/authorize');

		$json = [];

		$json['success'] = $this->language->get('text_link');

		// Create reset code
		$this->load->model('user/user');

		$this->model_user_user->addToken($this->user->getId(), 'authorize', oc_token(32));

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Reset
	 *
	 * @return void
	 */
	public function reset(): void {
		$this->load->language('common/authorize');

		if (isset($this->request->get['email'])) {
			$email = (string)urldecode($this->request->get['email']);
		} else {
			$email = '';
		}

		if (isset($this->request->get['code'])) {
			$code = (string)$this->request->get['code'];
		} else {
			$code = '';
		}

		$this->load->model('user/user');

		$user_info = $this->model_user_user->getTokenByCode($code);

		if ($user_info && $user_info['email'] === $email) {
			$this->model_user_user->editAuthorizeTotalByUserId($user_info['user_id'], 0);

			$this->model_user_user->editCode($email, '');

			$this->session->data['success'] = $this->language->get('text_unlocked');

			$this->response->redirect($this->url->link('common/authorize', 'user_token=' . $this->session->data['user_token'], true));
		} else {
			$this->user->logout();

			$this->model_user_user->editCode($email, '');

			$this->session->data['error'] = $this->language->get('error_reset');

			$this->response->redirect($this->url->link('common/login', '', true));
		}
	}
}
