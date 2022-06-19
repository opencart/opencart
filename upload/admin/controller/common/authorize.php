<?php
namespace Opencart\Admin\Controller\Common;
class Authorize extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('common/authorize');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->session->data['authorize'] = token(4);

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

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/authorize', $data));
	}

	private function validate(): void {
		$this->load->language('common/authorize');

		$json = [];
		$this->session->data['authorize'] = token(4);

		if (!isset($this->request->post['code']) || !isset($this->session->data['code']) || $this->request->post['code'] != $this->session->data['code']) {
			$json['error'] = $this->language->get('error_code');

			///$this->model_fraud_pin->addPin($this->user->getId());
		} else {
			//$this->model_fraud_pin->deletePin($this->member->getId());
		}

		$login_info = $this->model_user_user->getTotalAttempts($this->user->getId(), $this->request->get['code']);

		if ($login_info && $login_info['total'] >= 3) {
			$json['error'] = $this->language->get('error_retries');
		}

		if ($user_info) {
			$json['error'] = $this->language->get('error_user');
		}

		//$this->model_user_user->getTotalLoginsByCode($this->user->getId(), $this->request->get['code']);

		if (!$json) {
			$token = token(64);

			$login_data = [
				'token'      => $token,
				'ip'         => $this->request->server['REMOTE_ADDR'],
				'user_agent' => $this->request->server['HTTP_USER_AGENT'],
				'status'     => 1
			];

			$this->model_user_user->addLogin($token);

			setcookie('opencart', $token, time() + 60 * 60 * 24 * 365 * 10);

			//$this->model_user_user->addLogin($token);

			// Register the cookie for security.
			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], HTTP_SERVER) === 0)) {
				$json['redirect'] = str_replace('&amp;', '&', $this->request->post['redirect']) . '&user_token=' . $this->session->data['user_token'];
			} else {
				$json['redirect'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function resend() {
		$this->load->language('common/authorize');

		$json = [];

		$json['success'] = $this->language->get('text_reset');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function unlock() {
		$this->load->language('common/authorize');

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/authorize_unlock', $data));
	}

	public function reset() {
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

		$member_info = $this->model_user_user->getUserByEmail($email);

		if ($member_info && $member_info['code'] && $code && $member_info['code'] === $code) {
			$this->user->logout();

			$this->model_user_user->editPin($member_info['member_id'], '');

			$this->model_user_user->editCode($member_info['email'], '');

			$this->model_user_user->deletePin($member_info['member_id']);

			$this->session->data['success'] = 'Success: Your PIN has been reset!';
		} else {
			$this->model_user_user->editCode($email, '');

			$this->session->data['error'] = 'Warning: Could not reset your PIN!';
		}

		$this->response->redirect($this->url->link('account/login'));
	}

	public function save(): void {
		$this->load->language('common/pin');

		$json = [];

		if (!isset($this->request->post['code'])) {
			$json['error'] = $this->language->get('error_code');
		}

		if (!isset($this->session->data['authorize'])) {

		}

		if (!$json) {
			// Register the cookie for security.
			$this->load->model('user/user');

			$this->model_user_user->editPin($this->user->getId(), $this->request->post['pin']);

			if (empty($this->request->cookie['secure'])) {
				$token = token(32);

				setcookie('secure', $token, time() + 60 * 60 * 24 * 365 * 10);
			} else {
				$token = $this->request->cookie['secure'];
			}

			if (!$this->model_user_user->getLoginByToken($token)) {
				$login_data = [

				];

				$this->model_user_user->addLogin($token);
			}

			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], HTTP_SERVER) === 0)) {
				$json['redirect'] = str_replace('&amp;', '&', $this->request->post['redirect']) . '&user_token=' . $this->session->data['user_token'];
			} else {
				$json['redirect'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
