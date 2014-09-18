<?php
class ControllerModuleAffiliate extends Controller {
	public function install() {
		$this->load->model('extension/module');
		
		$this->model_extension_module->addModule('affiliate');
	}

	public function uninstall() {
		$this->language->load('extension/module');
		
		if (!$this->user->hasPermission('modify', 'extension/module')) {
			$this->load->model('extension/module');
			
			$this->model_extension_module->deleteModule('affiliate');
		} else {
			$this->session->data['error'] =	$this->language->load('error_permission');
			
			$this->response->redirect($this->url->link('extension/module'));
		}
	}
}