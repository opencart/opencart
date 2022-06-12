<?php
namespace Opencart\Admin\Controller\Common;
class Pin extends \Opencart\System\Engine\Controller {
	public function index() {
		$this->load->language('common/pin');

		// Make sure no one can override the PIN.
		if ($this->user->getPin()) {
			$this->response->redirect($this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['action'] = $this->url->link('common/pin|save', 'user_token=' . $this->session->data['user_token']);

		if (isset($this->session->data['redirect'])) {
			$data['redirect'] = $this->session->data['redirect'];
		} else {
			$data['redirect'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/pin', $data));
	}

	public function save(): void {
		$this->load->language('common/pin');

		$json = [];

		if (strlen($this->request->post['pin']) <= 4) {
			$json['error'] = $this->language->get('error_pin');
		}

		if (!$json) {
			// Register the cookie for security.
			$this->load->model('user/user');

			$this->model_user_user->editPin($this->user->getId(), $this->request->post['pin']);

			if (empty($this->request->cookie['opencart'])) {


				$cookie = $this->member->getCookie();

				setcookie('opencart', $cookie, time() + 60 * 60 * 24 * 365 * 10);
			} else {


				$cookie = $this->request->cookie['opencart'];
			}

			if (!$this->model_user_user->getTotalCookiesByCookie($cookie)) {
				$this->model_user_user->addAuth($cookie);
			} else {
				$this->model_user_user->edit($cookie);
			}



			if (isset($this->request->post['redirect']) && substr($this->request->post['redirect'], 0, strlen(HTTP_SERVER)) == HTTP_SERVER) {
				unset($this->session->data['redirect']);

				$json['redirect'] = str_replace('&amp;', '&', $this->request->post['redirect']) . '&user_token=' . $this->session->data['user_token'];
			} else {
				$json['redirect'] = $this->url->link('common/account', 'user_token=' . $this->session->data['user_token']);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function pin() {
		$this->document->setTitle('Account PIN');

		$this->load->model('account/member');

		$this->load->model('fraud/pin');

		//$data['pin_total'] = $this->model_fraud_pin->getPinByMemberId($this->member->getId());

		$data['action'] = $this->url->link('account/security', 'member_token=' . $this->session->data['member_token'], true);
		$data['contact'] = $this->url->link('support/contact');

		if (isset($this->session->data['redirect'])) {
			$data['redirect'] = $this->session->data['redirect'];
		} else {
			$data['redirect'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('account/security', $data));
	}

	private function validate() {
		$this->load->model('fraud/pin');

		$fields = array('pin');

		foreach ($fields as $field) {
			if (!isset($this->request->post[$field])) {
				$this->request->post[$field] = '';
			}
		}

		$pin_total = $this->model_fraud_pin->getPinByMemberId($this->member->getId());

		if ($pin_total >= 3) {
			$this->error['warning'] = 'PIN does not match!';
		} elseif (!isset($this->request->post['pin']) || ($this->member->getPin() != $this->request->post['pin'])) {
			$this->model_fraud_pin->addPin($this->member->getId());

			$this->error['warning'] = 'PIN does not match!';
		} else {
			$this->model_fraud_pin->deletePin($this->member->getId());
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
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

			if (isset($this->request->post['redirect']) && substr($this->request->post['redirect'], 0, strlen(HTTP_SERVER)) == HTTP_SERVER) {
				unset($this->session->data['redirect']);

				$this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']) . '&member_token=' . $this->session->data['member_token']);
			} else {
				$this->response->redirect($this->url->link('account/account', 'member_token=' . $this->session->data['member_token']));
			}
		}

		return !$this->error;
	}

	public function reset() {
		$this->load->language('common/pin');

		$json = array();

		$this->load->model('fraud/pin');

		if ($this->user->isLogged()) {
			$this->load->model('account/member');

			$this->model_account_member->editCode($this->member->getEmail(), token(40));

			$json['success'] = 'Success: An email with a reset link has been sent your email address!';
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

		$this->load->model('account/member');

		$member_info = $this->model_account_member->getMemberByEmail($email);

		if ($member_info && $member_info['code'] && $code && $member_info['code'] === $code) {
			$this->member->logout();

			$this->model_account_member->editPin($member_info['member_id'], '');

			$this->model_account_member->editCode($member_info['email'], '');

			$this->load->model('fraud/pin');

			$this->model_fraud_pin->deletePin($member_info['member_id']);

			$this->session->data['success'] = 'Success: Your PIN has been reset!';
		} else {
			$this->model_account_member->editCode($email, '');

			$this->session->data['error'] = 'Warning: Could not reset your PIN!';
		}

		$this->response->redirect($this->url->link('account/login'));
	}
}
