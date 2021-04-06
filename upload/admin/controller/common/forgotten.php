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

	public function reset(): void {
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

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
