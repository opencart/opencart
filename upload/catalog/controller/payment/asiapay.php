<?php
class ControllerPaymentAsiaPay extends Controller {
	protected function index() {
		
		
		
		$this->id       = 'payment';
		$this->template = $this->config->get('config_template') . 'payment/asiapay.tpl';
		
		$this->render();
	} 	
}
?>