<?php 
class ControllerTotalCoupon extends Controller {
	public function index() {
		$this->language->load('total/coupon');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_coupon'] = $this->language->get('entry_coupon');
		
		$this->data['button_coupon'] = $this->language->get('button_coupon');
				
		if (isset($this->session->data['coupon'])) {
			$this->data['coupon'] = $this->session->data['coupon'];
		} else {
			$this->data['coupon'] = '';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/total/coupon.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/total/coupon.tpl';
		} else {
			$this->template = 'default/template/total/coupon.tpl';
		}
					
		$this->render();
  	}
		
	public function calculate() {
		$this->language->load('total/coupon');
		
		$json = array();
		
		if (!$this->cart->hasProducts()) {
			$json['redirect'] = $this->url->link('checkout/cart');				
		}	
				
		if (isset($this->request->post['coupon'])) {
			$this->load->model('checkout/coupon');
	
			$coupon_info = $this->model_checkout_coupon->getCoupon($this->request->post['coupon']);			
			
			if ($coupon_info) {			
				$this->session->data['coupon'] = $this->request->post['coupon'];
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$json['redirect'] = $this->url->link('checkout/cart', '', 'SSL');
			} else {
				$json['error'] = $this->language->get('error_coupon');
			}
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));		
	}
}
?>