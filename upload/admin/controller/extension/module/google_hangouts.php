<?php
class ControllerExtensionModuleGoogleHangouts extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/google_hangouts');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_google_hangouts', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module'));
		}

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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/google_hangouts', 'user_token=' . $this->session->data['user_token'])
		);

		$data['action'] = $this->url->link('extension/module/google_hangouts', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

		if (isset($this->request->post['module_google_hangouts_code'])) {
			$data['module_google_hangouts_code'] = $this->request->post['module_google_hangouts_code'];
		} else {
			$data['module_google_hangouts_code'] = $this->config->get('module_google_hangouts_code');
		}

		if (isset($this->request->post['module_google_hangouts_status'])) {
			$data['module_google_hangouts_status'] = $this->request->post['module_google_hangouts_status'];
		} else {
			$data['module_google_hangouts_status'] = $this->config->get('module_google_hangouts_status');
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

		if (!$this->request->post['module_google_hangouts_code']) {
			$this->error['code'] = $this->language->get('error_code');
		}

		return !$this->error;
	}
}