<?php
namespace Opencart\Admin\Controller\Common;
class Authorize extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('common/authorize');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['action'] = $this->url->link('common/authorize|validate', 'user_token=' . $this->session->data['user_token']);

		if (isset($this->request->get['route']) && $this->request->get['route'] != 'common/login') {
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
			$data['redirect'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/authorize', $data));
	}

	public function send() {
		$this->load->language('common/authorize');

		$json = [];

		// Create a token that can be stored as a cookie and will be used to identify device is safe.
		if (!isset($this->session->data['authorize'])) {
			$token = token(32);

			$login_data = [
				'token'      => $token,
				'ip'         => $this->request->server['REMOTE_ADDR'],
				'user_agent' => $this->request->server['HTTP_USER_AGENT']
			];

			$this->load->model('user/user');

			$this->model_user_user->addLogin($this->user->getId(), $login_data);

			$this->session->data['authorize'] = $token;

			setcookie('opencart', $token, time() + 60 * 60 * 24 * 365 * 10);
		}

		// Set the code to be emailed
		$this->session->data['code'] = token(4);

		$json['success'] = $this->language->get('text_resend');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function validate(): void {
		$this->load->language('common/authorize');

		$json = [];

		if (isset($this->session->data['authorize'])) {
			$token = $this->session->data['authorize'];
		} else {
			$token = '';
		}

		$this->load->model('user/user');

		$login_info = $this->model_user_user->getLoginByToken($this->user->getId(), $token);

		if ($login_info) {
			if (($login_info['total'] < 3) && (!isset($this->request->post['code']) || !isset($this->session->data['code']) || ($this->request->post['code'] != $this->session->data['code']))) {
				$json['error'] = $this->language->get('error_code');

				$this->model_user_user->editLoginTotal($login_info['user_login_id'], $login_info['total'] + 1);
			}

			if ($login_info['total'] > 3) {
				$json['error'] = $this->language->get('error_locked');
			}
		} else {
			$json['error'] = $this->language->get('error_code');
		}

		if (!$json) {
			$this->model_user_user->editLoginStatus($login_info['user_login_id'], 1);
			$this->model_user_user->editLoginTotal($login_info['user_login_id'], 0);

			// Register the cookie for security.
			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], HTTP_SERVER) === 0)) {
				$json['redirect'] = str_replace('&amp;', '&', $this->request->post['redirect'] . '&user_token=' . $this->session->data['user_token']);
			} else {
				$json['redirect'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function unlock() {
		$this->load->language('common/authorize');

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/authorize_unlock', $data));
	}

	public function confirm() {
		$this->load->language('common/authorize');

		$json = [];

		$json['success'] = $this->language->get('text_link');

		// Create reset code
		$this->load->model('user/user');

		$this->model_user_user->editCode($this->user->getEmail(), token(32));

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function reset() {
		$this->load->language('common/authorize');

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

		$this->user->logout();

		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUserByEmail($email);

		if ($user_info && $user_info['code'] && $code && $user_info['code'] === $code) {
			$this->model_user_user->resetUserLogins($user_info['user_id']);

			$this->model_user_user->editCode($email, '');

			$this->session->data['success'] = $this->language->get('text_unlocked');
		} else {
			$this->model_user_user->editCode($email, '');

			$this->session->data['error'] = $this->language->get('error_reset');
		}

		$this->response->redirect($this->url->link('account/login'));
	}
}
