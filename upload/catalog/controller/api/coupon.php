<?php
class ControllerApiCoupon extends Controller {
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
				
			$json['success'] = $this->language->get('text_success');
		} else {
			$json['error'] = $this->language->get('error_voucher');
		}
		
		$this->response->setOutput(json_encode($json));		
	}
}