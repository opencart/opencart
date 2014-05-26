<?php
class ControllerApiVoucher extends Controller {
	public function index() {
		$this->load->language('module/voucher');
		
		$json = array();
				
		$this->load->model('checkout/voucher');
		
		if (isset($this->request->post['voucher'])) {
			$voucher = $this->request->post['voucher'];
		} else {
			$voucher = '';
		}
				
		$voucher_info = $this->model_checkout_voucher->getVoucher($voucher);			
		
		if ($voucher_info) {	
			$this->session->data['voucher'] = $this->request->post['voucher'];
				
			$this->session->data['success'] = $this->language->get('text_success');
				
			$json['redirect'] = $this->url->link('checkout/cart');
		} else {
			$json['error'] = $this->language->get('error_voucher');
		}
		
		$this->response->setOutput(json_encode($json));		
	}
	
	public function add() {
		
	}
	
	public function remove() {
		
	}
}