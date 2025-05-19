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

		// Check total attempts
		$this->load->model('user/user');

		$token_info = $this->model_user_user->getAuthorizeByToken($this->user->getId(), $token);

		if ($token_info && $token_info['total'] > 2) {
			$this->response->redirect($this->url->link('common/authorize.reset', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['action'] = $this->url->link('common/authorize.save', 'user_token=' . $this->session->data['user_token']);

		if (!$token_info) {
			// Create a token that can be stored as a cookie and will be used to identify device is safe.
			$token = oc_token(32);

			$authorize_data = [
				'token'      => $token,
				'ip'         => oc_get_ip(),
				'user_agent' => $this->request->server['HTTP_USER_AGENT']
			];

			$this->model_user_user->addAuthorize($this->user->getId(), $authorize_data);

			setcookie('admin_authorize', $token, time() + 60 * 60 * 24 * 90);
		}

		// Set the code to be emailed
		$this->session->data['code'] = oc_token(6);

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

		// 3. If token already exists, check if it's valid
		$this->load->model('user/user');

		$token_info = $this->model_user_user->getAuthorizeByToken($this->user->getId(), $token);

		if (!$token_info) {
			$json['redirect'] = $this->url->link('common/authorize', 'user_token=' . $this->session->data['user_token'], true);
			// If token is valid and total attempts are more than 2, redirect to unlock page.
		} elseif ($token_info['total'] > 2) {
			$json['redirect'] = $this->url->link('common/authorize.reset', 'user_token=' . $this->session->data['user_token'], true);
		}

		if (!$json) {
			// Set the code to be emailed
			$this->session->data['code'] = oc_token(6);

			$json['success'] = $this->language->get('text_sent');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('common/authorize');

		$json = [];

		$required = [
			'code'     => '',
			'redirect' => ''
		];

		$post_info = $this->request->post + $required;

		if (isset($this->request->cookie['admin_authorize'])) {
			$token = $this->request->cookie['admin_authorize'];
		} else {
			$token = '';
		}

		// 3. If token already exists, check if it's valid
		$this->load->model('user/user');

		$token_info = $this->model_user_user->getAuthorizeByToken($this->user->getId(), $token);

		if (!$token_info) {
			$json['redirect'] = $this->url->link('common/authorize', 'user_token=' . $this->session->data['user_token'], true);
		} elseif ($token_info['total'] > 2) {
			$json['redirect'] = $this->url->link('common/authorize.reset', 'user_token=' . $this->session->data['user_token'], true);
		} elseif (!isset($post_info['code']) || !isset($this->session->data['code']) || ($post_info['code'] != $this->session->data['code'])) {
			$total = $token_info['total'] + 1;

			if ($total <= 2) {
				$json['error'] = $this->language->get('error_code');
			} else {
				unset($this->session->data['code']);

				$json['redirect'] = $this->url->link('common/authorize.reset', 'user_token=' . $this->session->data['user_token'], true);
			}

			$this->model_user_user->editAuthorizeTotal($token_info['user_authorize_id'], $total);
		}

		if (!$json) {
			$this->model_user_user->editAuthorizeStatus($token_info['user_authorize_id'], true);
			$this->model_user_user->editAuthorizeTotal($token_info['user_authorize_id'], 0);

			if (isset($post_info['redirect'])) {
				$redirect = urldecode(html_entity_decode($post_info['redirect'], ENT_QUOTES, 'UTF-8'));
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
	public function reset(): void {
		$this->load->language('common/authorize');

		if (isset($this->request->cookie['admin_authorize'])) {
			$token = $this->request->cookie['admin_authorize'];
		} else {
			$token = '';
		}

		// Check total attempts
		$this->load->model('user/user');

		$token_info = $this->model_user_user->getAuthorizeByToken($this->user->getId(), $token);

		if (!$token_info || $token_info['total'] <= 2) {
			// Redirect if already have a valid token.
			$this->response->redirect($this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/authorize_reset', $data));
	}

	/**
	 * Confirm
	 *
	 * @return void
	 */
	public function confirm(): void {
		$this->load->language('common/authorize');

		$json = [];

		if (isset($this->request->cookie['admin_authorize'])) {
			$token = $this->request->cookie['admin_authorize'];
		} else {
			$token = '';
		}

		// Check total attempts
		$this->load->model('user/user');

		$token_info = $this->model_user_user->getAuthorizeByToken($this->user->getId(), $token);

		if (!$token_info || $token_info['total'] <= 2) {
			$json['redirect'] = $this->url->link('common/authorize', 'user_token=' . $this->session->data['user_token'], true);
		}

		if (!$json) {
			// Create reset code
			$this->model_user_user->addToken($this->user->getId(), 'authorize', oc_token(32));

			$json['success'] = $this->language->get('text_link');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Reset
	 *
	 * We have to keep the reset method from blocking requests because some email clients will block cross site requests.
	 *
	 * @return void
	 */
	public function unlock(): void {
		$this->load->language('common/authorize');

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

		$this->document->setTitle($this->language->get('heading_title'));

		// Check total attempts
		$this->load->model('user/user');

		$user_info = $this->model_user_user->getTokenByCode($code);

		if ($user_info && $user_info['email'] === $email) {
			$data['text_unlock'] = $this->language->get('text_unlock');

			$this->model_user_user->resetAuthorizes($user_info['user_id']);
		} else {
			$data['text_unlock'] = $this->language->get('text_failed');
		}

		// Reset token so it can't be used again
		$this->model_user_user->deleteTokenByCode($code);

		// Logout user
		$this->user->logout();

		// Remove user token if set
		unset($this->session->data['user_token']);

		$data['login'] = $this->url->link('common/login', '');

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/authorize_unlock', $data));
	}
}
