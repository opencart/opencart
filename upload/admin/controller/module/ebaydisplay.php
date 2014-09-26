<?php
class ControllerModuleEbaydisplay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/ebaydisplay');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('view/javascript/openbay/js/faq.js');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('ebaydisplay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->cache->delete('ebaydisplay');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_start_newest'] = $this->language->get('text_start_newest');
		$data['text_start_random'] = $this->language->get('text_start_random');

		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_keywords'] = $this->language->get('entry_keywords');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_site'] = $this->language->get('entry_site');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_module_add'] = $this->language->get('button_module_add');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['image'])) {
			$data['error_image'] = $this->error['image'];
		} else {
			$data['error_image'] = array();
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/ebaydisplay', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['action'] = $this->url->link('module/ebaydisplay', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$data['token'] = $this->session->data['token'];

		$data['modules'] = array();

		if (isset($this->request->post['ebaydisplay_module'])) {
			$data['modules'] = $this->request->post['ebaydisplay_module'];
		} elseif ($this->config->get('ebaydisplay_module')) {
			$data['modules'] = $this->config->get('ebaydisplay_module');
		}else{
			$data['modules'] = array();
		}
		if (isset($this->request->post['ebaydisplay_module_username'])) {
			$data['ebaydisplay_module_username'] = $this->request->post['ebaydisplay_module_username'];
		} elseif ($this->config->get('ebaydisplay_module_username')) {
			$data['ebaydisplay_module_username'] = $this->config->get('ebaydisplay_module_username');
		}else{
			$data['ebaydisplay_module_username'] = '';
		}
		if (isset($this->request->post['ebaydisplay_module_keywords'])) {
			$data['ebaydisplay_module_keywords'] = $this->request->post['ebaydisplay_module_keywords'];
		} elseif ($this->config->get('ebaydisplay_module_keywords')) {
			$data['ebaydisplay_module_keywords'] = $this->config->get('ebaydisplay_module_keywords');
		}else{
			$data['ebaydisplay_module_keywords'] = '';
		}
		if (isset($this->request->post['ebaydisplay_module_description'])) {
			$data['ebaydisplay_module_description'] = $this->request->post['ebaydisplay_module_description'];
		} elseif ($this->config->get('ebaydisplay_module_description')) {
			$data['ebaydisplay_module_description'] = $this->config->get('ebaydisplay_module_description');
		}else{
			$data['ebaydisplay_module_description'] = 0;
		}
		if (isset($this->request->post['ebaydisplay_module_limit'])) {
			$data['ebaydisplay_module_limit'] = $this->request->post['ebaydisplay_module_limit'];
		} elseif ($this->config->get('ebaydisplay_module_limit')) {
			$data['ebaydisplay_module_limit'] = $this->config->get('ebaydisplay_module_limit');
		}else{
			$data['ebaydisplay_module_limit'] = 10;
		}
		if (isset($this->request->post['ebaydisplay_module_sort'])) {
			$data['ebaydisplay_module_sort'] = $this->request->post['ebaydisplay_module_sort'];
		} elseif ($this->config->get('ebaydisplay_module_sort')) {
			$data['ebaydisplay_module_sort'] = $this->config->get('ebaydisplay_module_sort');
		}else{
			$data['ebaydisplay_module_sort'] = 'StartTimeNewest';
		}
		if (isset($this->request->post['ebaydisplay_module_site'])) {
			$data['ebaydisplay_module_site'] = $this->request->post['ebaydisplay_module_site'];
		} elseif ($this->config->get('ebaydisplay_module_sort')) {
			$data['ebaydisplay_module_site'] = $this->config->get('ebaydisplay_module_site');
		}else{
			$data['ebaydisplay_module_site'] = 3;
		}

		$data['ebay_sites'] = array(
			0 => 'USA',
			3 => 'UK',
			15 => 'Australia',
			2 => 'Canada (English)',
			71 => 'France',
			77 => 'Germany',
			101 => 'Italy',
			186 => 'Spain',
			205 => 'Ireland',
			16  => 'Austria',
			146 => 'Netherlands',
			23  => 'Belgium (French)',
			123 => 'Belgium (Dutch)',
		);

		if (isset($this->request->post['ebaydisplay_status'])) {
			$data['ebaydisplay_status'] = $this->request->post['ebaydisplay_status'];
		} else {
			$data['ebaydisplay_status'] = $this->config->get('ebaydisplay_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/ebaydisplay.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/ebaydisplay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}