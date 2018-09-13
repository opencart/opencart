<?php
class ControllerExtensionModuleGDPR extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/gdpr');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_gdpr', $_POST);

			$this->session->data['success'] = $this->language->get('text_success');
                        
                        $this->response->redirect($this->url->link('extension/module/gdpr', 'user_token=' . $this->session->data['user_token']));
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
			'href' => $this->url->link('extension/module/gdpr', 'user_token=' . $this->session->data['user_token'])
		);

		$data['action'] = $this->url->link('extension/module/gdpr', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

		if (isset($this->request->post['module_gdpr_status'])) {
			$data['module_gdpr_policy_url'] = $this->request->post['module_gdpr_policy_url'];
                        $data['module_gdpr_status'] = $this->request->post['module_gdpr_status'];
		} else {
			$data['module_gdpr_policy_url'] = $this->config->get('module_gdpr_policy_url');
                        $data['module_gdpr_status'] = $this->config->get('module_gdpr_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/gdpr', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/gdpr')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}