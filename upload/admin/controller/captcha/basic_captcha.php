<?php
class ControllerCaptchaBasicCaptcha extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('captcha/basic_captcha');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('basic_captcha', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/captcha', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
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
			'href' => $this->url->link('captcha/basic_captcha', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('captcha/basic_captcha', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/captcha', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['basic_captcha_status'])) {
			$data['basic_captcha_status'] = $this->request->post['basic_captcha_status'];
		} else {
			$data['basic_captcha_status'] = $this->config->get('basic_captcha_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('captcha/basic_captcha.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'captcha/basic_captcha')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
