<?php 
class ControllerTotalVoucher extends Controller {
	public function index() {
		$this->language->load('total/voucher');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_voucher'] = $this->language->get('entry_voucher');
		
		$this->data['button_voucher'] = $this->language->get('button_voucher');
				
		if (isset($this->session->data['voucher'])) {
			$this->data['voucher'] = $this->session->data['voucher'];
		} else {
			$this->data['voucher'] = '';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/total/voucher.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/total/voucher.tpl';
		} else {
			$this->template = 'default/template/total/voucher.tpl';
		}
					
		$this->render();
  	}
		
	public function calculate() {
		$this->language->load('total/voucher');
		
		$json = array();
		
		if (!$this->cart->hasProducts()) {
			$json['redirect'] = $this->url->link('checkout/cart');				
		}	
				
		if (isset($this->request->post['voucher'])) {
			$this->load->model('checkout/voucher');
	
			$voucher_info = $this->model_checkout_voucher->getVoucher($this->request->post['voucher']);			
			
			if ($voucher_info) {			
				$this->session->data['voucher'] = $this->request->post['voucher'];
				
				$this->session->data['success'] = $this->language->get('text_success');
				
				$json['redirect'] = $this->url->link('checkout/cart', '', 'SSL');
			} else {
				$json['error'] = $this->language->get('error_voucher');
			}
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));		
	}
}
?>