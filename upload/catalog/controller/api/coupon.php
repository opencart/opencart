<?php
class ControllerApiCoupon extends Controller {
	public function index() {
		$this->load->language('module/coupon');
		
		$json = array();
				
		$this->load->model('checkout/coupon');
		
		if (isset($this->request->post['coupon'])) {
			$coupon = $this->request->post['coupon'];
		} else {
			$coupon = '';
		}
				
		$coupon_info = $this->model_checkout_voucher->getVoucher($coupon);			
		
		if ($coupon_info) {	
			$this->session->data['coupon'] = $this->request->post['coupon'];
				
			$json['success'] = $this->language->get('text_success');
		} else {
			$json['error'] = $this->language->get('error_coupon');
		}
		
		$this->response->setOutput(json_encode($json));		
	}
}