<?php
class ControllerExtensionModulePPButton extends Controller {
	public function index() {
		$this->load->language('extension/module/pp_button');

		$this->load->model('setting/setting');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('pp_button', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_info'] = $this->language->get('text_info');
		$data['text_info_li1'] = $this->language->get('text_info_li1');
		$data['text_info_li2'] = $this->language->get('text_info_li2');
		$data['text_info_li3'] = $this->language->get('text_info_li3');
		$data['text_layouts'] = $this->language->get('text_layouts');
		$data['text_layout_link'] = $this->language->get('text_layout_link');

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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/pp_button', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/module/pp_button', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		$data['layouts'] = $this->url->link('design/layout', 'token=' . $this->session->data['token'], true);

		if (isset($this->request->post['pp_button_status'])) {
			$data['pp_button_status'] = $this->request->post['pp_button_status'];
		} else {
			$data['pp_button_status'] = $this->config->get('pp_button_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/pp_button', $data));
	}

	public function install() {
		$this->load->model('setting/setting');

		$settings['pp_button_status'] = 1;

		$this->model_setting_setting->editSetting('pp_button', $settings);
	}

	public function configure() {
		$this->load->language('extension/extension/module');

		if (!$this->user->hasPermission('modify', 'extension/extension/module')) {
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], true));
		} else {
			$this->load->model('extension/extension');
			$this->load->model('extension/module');
			$this->load->model('user/user_group');

			$this->model_extension_extension->install('module', 'pp_button');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/pp_button');
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/pp_button');

			$this->install();

			$this->response->redirect($this->url->link('design/layout', 'token=' . $this->session->data['token'], true));
		}
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/pp_button')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}