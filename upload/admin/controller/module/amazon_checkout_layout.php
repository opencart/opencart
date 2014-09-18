<?php
class ControllerModuleAmazonCheckoutLayout extends Controller {
	public function install() {
		$this->load->model('extension/module');
		
		$this->model_extension_module->addModule('checkout_layout');
	}

	public function uninstall() {
		$this->load->model('extension/module');
		
		$this->model_extension_module->deleteModule('checkout_layout');
	}
}