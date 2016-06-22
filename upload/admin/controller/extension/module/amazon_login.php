<?php
class ControllerExtensionModuleAmazonLogin extends Controller {

	private $error = array();

	public function index() {

		$this->load->language('extension/module/amazon_login');

		$this->load->model('setting/setting');
		$this->load->model('design/layout');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('amazon_login', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_content_top'] = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_column_right'] = $this->language->get('text_column_right');
		$data['text_lwa_button'] = $this->language->get('text_lwa_button');
		$data['text_login_button'] = $this->language->get('text_login_button');
		$data['text_a_button'] = $this->language->get('text_a_button');
		$data['text_gold_button'] = $this->language->get('text_gold_button');
		$data['text_darkgray_button'] = $this->language->get('text_darkgray_button');
		$data['text_lightgray_button'] = $this->language->get('text_lightgray_button');
		$data['text_small_button'] = $this->language->get('text_small_button');
		$data['text_medium_button'] = $this->language->get('text_medium_button');
		$data['text_large_button'] = $this->language->get('text_large_button');
		$data['text_x_large_button'] = $this->language->get('text_x_large_button');

		$data['entry_button_type'] = $this->language->get('entry_button_type');
		$data['entry_button_colour'] = $this->language->get('entry_button_colour');
		$data['entry_button_size'] = $this->language->get('entry_button_size');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_module_add'] = $this->language->get('button_module_add');
		$data['button_remove'] = $this->language->get('button_remove');

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
			'href' => $this->url->link('extension/module/amazon_login', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/module/amazon_login', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['amazon_login_button_type'])) {
			$data['amazon_login_button_type'] = $this->request->post['amazon_login_button_type'];
		} elseif ($this->config->get('amazon_login_button_type')) {
			$data['amazon_login_button_type'] = $this->config->get('amazon_login_button_type');
		} else {
			$data['amazon_login_button_type'] = 'LwA';
		}

		if (isset($this->request->post['amazon_login_button_colour'])) {
			$data['amazon_login_button_colour'] = $this->request->post['amazon_login_button_colour'];
		} elseif ($this->config->get('amazon_login_button_colour')) {
			$data['amazon_login_button_colour'] = $this->config->get('amazon_login_button_colour');
		} else {
			$data['amazon_login_button_colour'] = 'gold';
		}

		if (isset($this->request->post['amazon_login_button_size'])) {
			$data['amazon_login_button_size'] = $this->request->post['amazon_login_button_size'];
		} elseif ($this->config->get('amazon_login_button_size')) {
			$data['amazon_login_button_size'] = $this->config->get('amazon_login_button_size');
		} else {
			$data['amazon_login_button_size'] = 'medium';
		}

		if (isset($this->request->post['amazon_login_status'])) {
			$data['amazon_login_status'] = $this->request->post['amazon_login_status'];
		} else {
			$data['amazon_login_status'] = $this->config->get('amazon_login_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/amazon_login', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/amazon_login')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {
			$this->load->model('extension/event');
			$this->model_extension_event->addEvent('amazon_login', 'catalog/controller/account/logout/after', 'extension/module/amazon_login/logout');
	}

	public function uninstall() {
		$this->load->model('extension/event');
		$this->model_extension_event->deleteEvent('amazon_login');
	}

}