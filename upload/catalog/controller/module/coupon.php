<?php
class ControllerModuleCoupon extends Controller {
	public function index() {
		$this->language->load('module/coupon');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_loading'] = $this->language->get('text_loading');
	
		$this->data['entry_coupon'] = $this->language->get('entry_coupon');
		
		$this->data['button_coupon'] = $this->language->get('button_coupon');
		
		$this->data['status'] = $this->config->get('coupon_status');
		
		if (isset($this->session->data['coupon'])) {
			$this->data['coupon'] = $this->session->data['coupon'];
		} else {
			$this->data['coupon'] = '';
		}			
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/coupon.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/coupon.tpl';
		} else {
			$this->template = 'default/template/module/coupon.tpl';
		}
					
		$this->response->setOutput($this->render());		
	}
	
	public function coupon() {
		$this->language->load('module/coupon');
		
		$json = array();
				
		$this->load->model('checkout/coupon');
		
		if (isset($this->request->post['coupon'])) {
			$coupon = $this->request->post['coupon'];
		} else {
			$coupon = '';
		}
						
		$coupon_info = $this->model_checkout_coupon->getCoupon($coupon);			
		
		if ($coupon_info) {			
			$this->session->data['coupon'] = $this->request->post['coupon'];
				
			$this->session->data['success'] = $this->language->get('text_coupon');
			
			$json['redirect'] = $this->url->link('checkout/cart');
		} else {
			$json['error'] = $this->language->get('error_coupon');			
		}
					
		$this->response->setOutput(json_encode($json));	
	}
}
?>