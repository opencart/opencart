<?php
class ControllerCaptchaGoogleCaptcha extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('captcha/google_captcha');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('google_captcha', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/captcha', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_signup'] = $this->language->get('text_signup');

		$data['entry_key'] = $this->language->get('entry_key');
		$data['entry_secret'] = $this->language->get('entry_secret');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['key'])) {
			$data['error_key'] = $this->error['key'];
		} else {
			$data['error_key'] = '';
		}

		if (isset($this->error['secret'])) {
			$data['error_secret'] = $this->error['secret'];
		} else {
			$data['error_secret'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_captcha'),
			'href' => $this->url->link('extension/captcha', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('captcha/google_captcha', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('captcha/google_captcha', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/captcha', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['google_captcha_key'])) {
			$data['google_captcha_key'] = $this->request->post['google_captcha_key'];
		} else {
			$data['google_captcha_key'] = $this->config->get('google_captcha_key');
		}

		if (isset($this->request->post['google_captcha_secret'])) {
			$data['google_captcha_secret'] = $this->request->post['google_captcha_secret'];
		} else {
			$data['google_captcha_secret'] = $this->config->get('google_captcha_secret');
		}

		if (isset($this->request->post['google_captcha_status'])) {
			$data['google_captcha_status'] = $this->request->post['google_captcha_status'];
		} else {
			$data['google_captcha_status'] = $this->config->get('google_captcha_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('captcha/google_captcha.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'captcha/google_captcha')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['google_captcha_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		if (!$this->request->post['google_captcha_secret']) {
			$this->error['secret'] = $this->language->get('error_secret');
		}

		return !$this->error;
	}
}
