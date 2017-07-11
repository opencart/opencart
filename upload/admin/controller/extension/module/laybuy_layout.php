<?php
class ControllerExtensionModuleLaybuyLayout extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('setting/setting');

		$this->load->language('extension/module/laybuy_layout');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_laybuy_layout', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/laybuy_layout', 'user_token=' . $this->session->data['user_token'], true)
		);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('extension/module/laybuy_layout', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_laybuy_layout_status'])) {
			$data['module_laybuy_layout_status'] = $this->request->post['module_laybuy_layout_status'];
		} else {
			$data['module_laybuy_layout_status'] = $this->config->get('module_laybuy_layout_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/laybuy_layout', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/laybuy_layout')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}