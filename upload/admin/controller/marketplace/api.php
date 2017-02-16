<?php
class ControllerMarketplaceApi extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('marketplace/api');

		$this->document->setTitle($this->language->get('heading_title'));		

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('api', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'], true));
		}
				
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');		
		$data['text_api'] = $this->language->get('text_api');

		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_secret'] = $this->language->get('entry_secret');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
	
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}

		if (isset($this->error['secret'])) {
			$data['error_secret'] = $this->error['secret'];
		} else {
			$data['error_secret'] = '';
		}
				
		$data['action'] = $this->url->link('marketplace/api', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/marketplace', 'user_token=' . $this->session->data['user_token'], true);
		
		if (isset($this->request->post['api_username'])) {
			$data['api_username'] = $this->request->post['api_username'];
		} else {
			$data['api_username'] = $this->config->get('api_username');
		}
		
		if (isset($this->request->post['api_secret'])) {
			$data['api_secret'] = $this->request->post['api_secret'];
		} else {
			$data['api_secret'] = '';
		}
	
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('marketplace/api', $data));	
	}
	
	public function validate() {
		if (!$this->user->hasPermission('modify', 'marketplace/marketplace')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}		
		
		if (!$this->request->post['api_username']) {
			$this->error['username'] = $this->language->get('api_username');
		}

		if (!$this->request->post['api_secret']) {
			$this->error['secret'] = $this->language->get('error_secret');
		}
		
		return !$this->error;
	}	
}