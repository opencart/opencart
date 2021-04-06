<?php
namespace Opencart\Admin\Controller\Common;
class Forgotten extends \Opencart\System\Engine\Controller {
	public function index(): void {
		if ($this->user->isLogged() && isset($this->request->get['user_token']) && ($this->request->get['user_token'] == $this->session->data['user_token'])) {
			$this->response->redirect($this->url->link('common/dashboard'));
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

	public function reset(): void {
		$this->load->language('common/forgotten');

		$json = [];

		if ($this->user->isLogged() && isset($this->request->get['user_token']) && ($this->request->get['user_token'] == $this->session->data['user_token'])) {
			$json['redirect'] = $this->url->link('common/dashboard');
		}

		if (!$this->config->get('config_password')) {
			$json['redirect'] = $this->url->link('common/login');
		}

		$this->load->model('user/user');

		if (!isset($this->request->post['email'])) {
			$json['error'] = $this->language->get('error_email');
		} elseif (!$this->model_user_user->getTotalUsersByEmail($this->request->post['email'])) {
			$json['error'] = $this->language->get('error_email');
		}

		if (!$json) {
			$this->model_user_user->editCode($this->request->post['email'], token(40));

			$this->session->data['success'] = $this->language->get('text_success');

			$json['redirect'] = $this->url->link('common/login');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
