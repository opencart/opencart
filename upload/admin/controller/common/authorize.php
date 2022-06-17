<?php
namespace Opencart\Admin\Controller\Common;
class Authorize extends \Opencart\System\Engine\Controller {
	public function index(): void {
() {		$this->load->language('common/authorize');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->load->model('user/user');

			if

		}

		$login_total = $this->model_user_user->getTotalLoginsByUserId($this->user->getId());

		if ($login_total > 3) {
			$data['error_warning'] = $this->language->get('error_warning');
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('common/authorize|validate', 'user_token=' . $this->session->data['user_token']);

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

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/authorize', $data));
	}

	private function validate(): void {
		$this->load->language('common/pin');

		$json = [];

		$this->load->model('fraud/pin');

		$pin_total = $this->model_user_user->getTotalLoginsByUserId($this->user->getId());

		if ($pin_total >= 3) {

			$json['error'] = $this->language->get('error_match');

		} elseif (!isset($this->request->post['pin']) || ($this->user->getPin() != $this->request->post['pin'])) {

			$this->model_fraud_pin->addPin($this->user->getId());

			$json['error'] = 'PIN does not match!';

		} else {

			$this->model_fraud_pin->deletePin($this->member->getId());
		}

		if (!$json) {
			// Register the cookie for security.
			if (empty($this->request->cookie['opencart'])) {
				setcookie('opencart', $this->member->getCookie(), time() + 60 * 60 * 24 * 365 * 10);

				$cookie = $this->member->getCookie();
			} else {
				$cookie = $this->request->cookie['opencart'];
			}

			if (!$this->model_account_member->getTotalCookiesByCookie($cookie)) {
				$this->model_account_member->addCookie($cookie);
			} else {
				$this->model_account_member->editCookie($cookie);
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

	public function setup() {
		$this->load->language('common/pin');

		// Make sure no one can override the PIN.
		if ($this->user->getPin()) {
			$this->response->redirect($this->url->link('common/pin', 'user_token=' . $this->session->data['user_token']));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['action'] = $this->url->link('common/pin|save', 'user_token=' . $this->session->data['user_token']);

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

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/pin_setup', $data));
	}

	public function save(): void {
		$this->load->language('common/pin');

		$json = [];

		if (strlen($this->request->post['pin']) < 4) {
			$json['error'] = $this->language->get('error_pin');
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

	public function reset() {
		$this->load->language('common/pin');

		$json = array();

		if (!$json) {
			$this->load->model('user/user');

			$this->model_user_user->editUser($this->user->getEmail(), token(40));

			$json['success'] = $this->language->get('text_link');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function confirm() {
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
}
