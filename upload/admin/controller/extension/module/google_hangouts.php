<?php
class ControllerExtensionModuleGoogleHangouts extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/google_hangouts');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('google_hangouts', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_code'] = $this->language->get('entry_code');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['help_code'] = $this->language->get('help_code');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['code'])) {
			$data['error_code'] = $this->error['code'];
		} else {
			$data['error_code'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/google_hangouts', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/module/google_hangouts', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->post['google_hangouts_code'])) {
			$data['google_hangouts_code'] = $this->request->post['google_hangouts_code'];
		} else {
			$data['google_hangouts_code'] = $this->config->get('google_hangouts_code');
		}

		if (isset($this->request->post['google_hangouts_status'])) {
			$data['google_hangouts_status'] = $this->request->post['google_hangouts_status'];
		} else {
			$data['google_hangouts_status'] = $this->config->get('google_hangouts_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/google_hangouts', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/google_hangouts')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['google_hangouts_code']) {
			$this->error['code'] = $this->language->get('error_code');
		}

		return !$this->error;
	}
}