<?php
class ControllerModuleVoucher extends Controller {
	public function index() {
		$this->language->load('module/voucher');
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_loading'] = $this->language->get('text_loading');
		
		$this->data['entry_voucher'] = $this->language->get('entry_voucher');
		
		$this->data['button_voucher'] = $this->language->get('button_voucher');
		
		$this->data['status'] = $this->config->get('voucher_status');
		
		if (isset($this->session->data['voucher'])) {
			$this->data['voucher'] = $this->session->data['voucher'];
		} else {
			$this->data['voucher'] = '';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/voucher.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/voucher.tpl';
		} else {
			$this->template = 'default/template/module/voucher.tpl';
		}
					
		$this->response->setOutput($this->render());		
	}
	
	public function voucher() {
		$this->language->load('module/voucher');
		
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
				
			$this->session->data['success'] = $this->language->get('text_voucher');
				
			$json['redirect'] = $this->url->link('checkout/cart');			
		} else {
			$json['error'] = $this->language->get('error_voucher');
		}
		
		$this->response->setOutput(json_encode($json));		
	}
}
?>