<?php
class ControllerPaymentAsiaPay extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/asiapay.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/asiapay.tpl';
		} else {
			$this->template = 'default/template/payment/asiapay.tpl';
		}		
		
		$this->render();
	} 	
}
?>