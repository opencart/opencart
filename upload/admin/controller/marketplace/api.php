<?php
namespace Opencart\Admin\Controller\Marketplace;
class Api extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('marketplace/api');
			
		$data['user_token'] = $this->session->data['user_token'];	
			
		$this->response->setOutput($this->load->view('marketplace/api', $data));
	}
	
	public function save(): void {
		$this->load->language('marketplace/api');

		$json = [];
		
		if (!$this->user->hasPermission('modify', 'marketplace/api')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['opencart_username']) {
			$json['error']['username'] = $this->language->get('error_username');
		}

		if (!$this->request->post['opencart_secret']) {
			$json['error']['secret'] = $this->language->get('error_secret');
		}		

		if (!$json) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('opencart', $this->request->post);
			
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}