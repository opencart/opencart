<?php
namespace Opencart\Admin\Controller\Common;
/**
 * Class Authorize
 *
 * @package Opencart\Admin\Controller\Common
 */
class Authorize extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('common/authorize');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->cookie['authorize'])) {
			$token = $this->request->cookie['authorize'];
		} else {
			$token = '';
		}

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
				'ip'         => $this->request->server['REMOTE_ADDR'],
				'user_agent' => $this->request->server['HTTP_USER_AGENT']
			];

			$this->load->model('user/user');

			$this->model_user_user->addAuthorize($this->user->getId(), $authorize_data);

			setcookie('authorize', $token, time() + 60 * 60 * 24 * 365 * 10);
		}

		$data['action'] = $this->url->link('common/authorize.validate', 'user_token=' . $this->session->data['user_token']);

		// Set the code to be emailed
		$this->session->data['code'] = oc_token(4);

		if (isset($this->request->get['route']) && $this->request->get['route'] != 'common/login' && $this->request->get['route'] != 'common/authorize') {
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
	 * @return void
	 */
	public function send() {
		$this->load->language('common/authorize');

		$json = [];

		$json['success'] = $this->language->get('text_resend');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return void
	 */
	public function validate(): void {
		$this->load->language('common/authorize');

		$json = [];

		if (isset($this->request->cookie['authorize'])) {
			$token = $this->request->cookie['authorize'];
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
			$this->model_user_user->editAuthorizeStatus($authorize_info['user_authorize_id'], 1);
			$this->model_user_user->editAuthorizeTotal($authorize_info['user_authorize_id'], 0);

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

	/**
	 * @return void
	 */
	public function unlock() {
		$this->load->language('common/authorize');

		if (isset($this->request->cookie['authorize'])) {
			$token = $this->request->cookie['authorize'];
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
	 * @return void
	 */
	public function confirm() {
		$this->load->language('common/authorize');

		$json = [];

		$json['success'] = $this->language->get('text_link');

		// Create reset code
		$this->load->model('user/user');

		$this->model_user_user->editCode($this->user->getEmail(), oc_token(32));

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return void
	 */
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

		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUserByEmail($email);

		if ($user_info && $user_info['code'] && $code && $user_info['code'] === $code) {
			$this->model_user_user->resetAuthorizes($user_info['user_id']);

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
