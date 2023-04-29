<?php
namespace Opencart\Admin\Controller\Common;
class Forgotten extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('common/forgotten');

		if ($this->user->isLogged()) {
			$this->response->redirect($this->url->link('common/login'));
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

	public function confirm(): void {
		$this->load->language('common/forgotten');

		$json = [];

		// Stop any undefined index messages.
		if ($this->user->isLogged()) {
			$json['redirect'] = $this->url->link('common/login', '', true);
		}

		$keys = ['email'];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUserByEmail($this->request->post['email']);

		if (!$user_info) {
			$json['error'] = $this->language->get('error_email');
		}

		if (!$json) {
			$this->model_user_user->editCode($this->request->post['email'], oc_token(40));

			$this->session->data['success'] = $this->language->get('text_success');

			$json['redirect'] = $this->url->link('common/login', '', true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

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

		if ($this->user->isLogged()) {
			$this->response->redirect($this->url->link('common/login'));
		}

		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUserByEmail($email);

		if (!$user_info || !$user_info['code'] || $user_info['code'] !== $code) {
			$this->model_user_user->editCode($email, '');

			$this->session->data['error'] = $this->language->get('error_code');

			$this->response->redirect($this->url->link('common/login'));
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

		$this->session->data['reset_token'] = substr(bin2hex(openssl_random_pseudo_bytes(26)), 0, 26);

		$data['reset'] = $this->url->link('common/forgotten.password', 'email=' . urlencode($email) . '&code=' . $code . '&reset_token=' . $this->session->data['reset_token']);
		$data['back'] = $this->url->link('common/login');

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/forgotten_reset', $data));
	}

	public function password(): void {
		$this->load->language('common/forgotten');

		$json = [];

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

		$keys = [
			'password',
			'confirm'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		if (!isset($this->request->get['reset_token']) || !isset($this->session->data['reset_token']) || ($this->session->data['reset_token'] != $this->request->get['reset_token'])) {
			$this->session->data['error'] = $this->language->get('error_session');

			$json['redirect'] = $this->url->link('account/forgotten', true);
		}

		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUserByEmail($email);

		if (!$user_info || !$user_info['code'] || $user_info['code'] !== $code) {
			$this->model_user_user->editCode($email, '');

			$this->session->data['error'] = $this->language->get('error_code');

			$json['redirect'] = $this->url->link('common/login', '', true);
		}

		if (!$json) {
			if ((oc_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (oc_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
				$json['error']['password'] = $this->language->get('error_password');
			}

			if ($this->request->post['confirm'] != $this->request->post['password']) {
				$json['error']['confirm'] = $this->language->get('error_confirm');
			}
		}

		if (!$json) {
			$this->model_user_user->editPassword($user_info['user_id'], $this->request->post['password']);

			$this->session->data['success'] = $this->language->get('text_reset');

			unset($this->session->data['reset_token']);

			$json['redirect'] = $this->url->link('common/login', '', true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
