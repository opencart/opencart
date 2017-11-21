<?php
class ControllerCommonReset extends Controller {
	private $error = array();

	public function index() {
		if ($this->user->isLogged() && isset($this->request->get['user_token']) && ($this->request->get['user_token'] == $this->session->data['user_token'])) {
			$this->response->redirect($this->url->link('common/dashboard'));
		}

		$this->load->language('common/reset');

		if (!$this->config->get('config_password')) {
			$this->session->data['error'] = $this->language->get('error_disabled');

			$this->response->redirect($this->url->link('common/login'));
		}

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

		$this->load->model('user/user');

		$user_info = $this->model_user_user->getUserByEmail($email);

		if (!$user_info || !$user_info['code'] || $user_info['code'] !== $code) {
			$this->session->data['error'] = $this->language->get('error_code');

			$this->model_user_user->editCode($email, '');

			$this->load->model('setting/setting');

			$this->model_setting_setting->editSettingValue('config', 'config_password', '0');

			$this->response->redirect($this->url->link('common/login'));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_user_user->editPassword($user_info['user_id'], $this->request->post['password']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('common/login'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/reset')
		);

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['confirm'])) {
			$data['error_confirm'] = $this->error['confirm'];
		} else {
			$data['error_confirm'] = '';
		}

		$data['action'] = $this->url->link('common/reset', 'email=' . urlencode($email) . '&code=' . $code);

		$data['cancel'] = $this->url->link('common/login');

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->post['confirm'])) {
			$data['confirm'] = $this->request->post['confirm'];
		} else {
			$data['confirm'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/reset', $data));
	}

	protected function validate() {
		if ((utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if ($this->request->post['confirm'] != $this->request->post['password']) {
			$this->error['confirm'] = $this->language->get('error_confirm');
		}

		return !$this->error;
	}
}
