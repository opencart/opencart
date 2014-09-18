<?php
class ControllerModuleAccount extends Controller {
	public function install() {
		$this->load->model('extension/module');
		
		$this->model_extension_module->addModule('account');
	}

	public function uninstall() {
		$this->load->model('extension/module');
		
		$this->model_extension_module->deleteModule('account');
	}	
}