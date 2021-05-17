<?php
namespace Opencart\Admin\Controller\Common;
class Forgotten extends \Opencart\System\Engine\Controller {
	public function index(): void {
		if ($this->user->isLogged()) {
			$this->user->logout();

			$this->response->redirect($this->url->link('common/login'));
		}

		if (!$this->config->get('config_password')) {
			$this->response->redirect($this->url->link('common/login'));
		}

		$this->load->language('common/forgotten');

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

		$data['back'] = $this->url->link('common/login');

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/forgotten', $data));
	}

	public function confirm(): void {
		$this->load->language('common/forgotten');

		$json = [];

		if (isset($this->request->post['email'])) {
			$email = $this->request->post['email'];
		} else {
			$email = '';
		}

		// Stop any undefined index messages.
		if ($this->user->isLogged()) {
			$this->user->logout();

			$json['redirect'] = $this->url->link('common/login');
		}

		if (!$this->config->get('config_password')) {
			$json['redirect'] = $this->url->link('common/login');
		}

		$this->load->model('user/user');

		if (!$this->model_user_user->getTotalUsersByEmail($email)) {
			$json['error'] = $this->language->get('error_email');
		}

		if (!$json) {
			$this->model_user_user->editCode($email, token(40));

			$this->session->data['success'] = $this->language->get('text_success');

			$json['redirect'] = $this->url->link('common/login');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function reset(): void {
		$this->load->language('common/forgotten');

		if (isset($this->request->get['email'])) {
			$email = $this->request->get['email'];
		} else {
			$email = '';
		}

		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}

		if ($this->user->isLogged()) {
			$this->user->logout();

			$this->response->redirect($this->url->link('common/login'));
		}

		if (!$this->config->get('config_password')) {
			$this->session->data['error'] = $this->language->get('error_disabled');

			$this->response->redirect($this->url->link('common/login'));
		}

		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUserByEmail($email);

		if (!$user_info || !$user_info['code'] || $user_info['code'] !== $code) {
			$this->session->data['error'] = $this->language->get('error_code');

			$this->model_user_user->editCode($email, '');

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
			'href' => $this->url->link('common/reset')
		];

		$data['action'] = $this->url->link('common/forgotten|reset', 'email=' . urlencode($email) . '&code=' . $code);

		$data['back'] = $this->url->link('common/login');

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/forgotten_reset', $data));
	}

	public function password(): bool {
		$this->load->language('common/reset');

		$json = [];

		if (isset($this->request->get['email'])) {
			$email = $this->request->get['email'];
		} else {
			$email = '';
		}

		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}

		if ($this->user->isLogged()) {
			$this->user->logout();

			$json['redirect'] = $this->url->link('common/login');
		}

		if ((utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
			$json['error']['password'] = $this->language->get('error_password');
		}

		if ($this->request->post['confirm'] != $this->request->post['password']) {
			$json['error']['confirm'] = $this->language->get('error_confirm');
		}

		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUserByEmail($email);

		if (!$user_info) {
			$this->model_user_user->editCode($email, '');

			$this->session->data['error'] = $this->language->get('error_code');

			$json['redirect'] = $this->url->link('common/login');
		}

		if (!$json) {
			$this->load->model('user/user');

			$this->model_user_user->editPassword($user_info['user_id'], $this->request->post['password']);

			$this->session->data['success'] = $this->language->get('text_success');

			$json['redirect'] = $this->url->link('common/login');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
