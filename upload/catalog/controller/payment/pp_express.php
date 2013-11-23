<?php
class ControllerPaymentPPExpress extends Controller {
	public function index() {
    	$data['button_confirm'] = $this->language->get('button_confirm');

		if (!$this->config->get('pp_express_test')) {
    		$data['action'] = 'https://www.pp_express.com/cgi-bin/webscr';
  		} else {
			$data['action'] = 'https://www.sandbox.pp_express.com/cgi-bin/webscr';
		}		
		
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if (!$this->config->get('pp_direct_test')) {
			$api_endpoint = 'https://api-3t.pp.com/nvp';
		} else {
			$api_endpoint = 'https://api-3t.sandbox.pp.com/nvp';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pp_express.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/pp_express.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/pp_express.tpl', $data);
		}			
	}
}