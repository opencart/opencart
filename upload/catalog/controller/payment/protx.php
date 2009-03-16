<?php
class ControllerPaymentProtx extends Controller {
	protected function index() {
    	$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

		if (!$this->config->get('paypal_test')) {
    		$this->data['action'] = 'https://ukvps.protx.com/vspgateway/service/vspform-register.vsp';

			$vendor   = $this->config->get('protx_vendor');
			$password = $this->config->get('protx_password');
  		} else {
			$this->data['action'] = 'https://ukvpstest.protx.com/vspgateway/service/vspform-register.vsp';
			
			$vendor   = 'testvendor';
			$password = 'testvendor';			
		}		
		
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$query  = 'VendorTxCode=' . $this->session->data['order_id'];
		$query .= '&Amount=' . $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
		$query .= '&Currency=' . $order_info['currency'];
		$query .= '&Description=' . date($this->language->get('date_format_short')) . ' - ' . $order_info['firstname'] . ' ' . $order_info['lastname'];
		$query .= '&SuccessURL=' . $this->url->https('checkout/success');
		$query .= '&FailureURL=' . $this->url->https('checkout/payment');
		$query .= '&CustomerName=' . $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		$query .= '&CustomerEMail=' . $order_info['email'];
		$query .= '&VendorEMail=' . $this->config->get('config_email');

		$data = array();

		for ($i = 0; $i < strlen($password); $i++) {
			$data[$i] = ord(substr($password, $i, 1));
		}

		$output = '';

		for ($i = 0; $i < strlen($query); $i++) {
    		$output .= chr(ord(substr($query, $i, 1)) ^ ($data[$i % strlen($password)]));
		}

		$crypt = base64_encode($output);	
	
		$this->data['vendor'] = $vendor;
		$this->data['crypt'] = $crypt;

		$this->data['back'] = $this->url->https('checkout/payment');
		
		$this->id       = 'payment';
		$this->template = $this->config->get('config_template') . 'payment/protx.tpl';
		
		$this->render();		
	}
	
	public function confirm() {
		$this->load->model('checkout/order');
		
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
	}
}
?>