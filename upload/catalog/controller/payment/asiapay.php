<?php
class ControllerPaymentAsiaPay extends Controller {
	protected function index() {
    	$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');
		
		
		
		$this->id       = 'payment';
		$this->template = $this->config->get('config_template') . 'payment/asiapay.tpl';
		
		$this->render();
	} 	
}
?>