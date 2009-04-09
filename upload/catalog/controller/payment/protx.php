<?php
class ControllerPaymentProtx extends Controller {
	protected function index() {
    	$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

		if ($this->config->get('protx_test') == 'sim') {
    		$this->data['action'] = 'https://ukvpstest.protx.com/VSPSimulator/VSPFormGateway.asp';

			$vendor   = $this->config->get('protx_vendor');
			$password = $this->config->get('protx_password');	
  		} elseif ($this->config->get('protx_test') == 'test') {
			$this->data['action'] = 'https://ukvpstest.protx.com/vps2form/submit.asp';
			
			$vendor   = 'testvendor';
			$password = 'testvendor';			
		} elseif ($this->config->get('protx_test') == 'live') {
    		$this->data['action'] = 'https://ukvps.protx.com/vps2form/submit.asp';

			$vendor   = $this->config->get('protx_vendor');
			$password = $this->config->get('protx_password');		
		}		
		
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$query  = 'VendorTxCode=' . date("dmYHis") . $this->session->data['order_id'];
		$query .= '&Amount=' . $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
		$query .= '&Currency=' . $order_info['currency'];
		$query .= '&Description=' . sprintf($this->language->get('description'), date($this->language->get('date_format_short')), $this->session->data['order_id']);
		$query .= '&SuccessURL=' . $this->url->https('checkout/success');
		$query .= '&FailureURL=' . $this->url->https('checkout/payment');
		$query .= '&CustomerName=' . $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		$query .= '&ContactNumber=' .   $order_info['telephone'];
		
		if ($order_info['shipping_address_2']) {
			$query .= '&DeliveryAddress=' . $order_info['shipping_address_1'] . "\r\n" . $order_info['shipping_address_2'] . "\r\n" . $order_info['shipping_city'] . "\r\n" . $order_info['shipping_zone'] . "\r\n";
		} else {
			$query .= '&DeliveryAddress=' . $order_info['shipping_address_1'] . "\r\n" . $order_info['shipping_city'] . "\r\n" . $order_info['shipping_zone'] . "\r\n";
		}
		
		$query .= '&DeliveryPostCode=' . $order_info['shipping_postcode'];
		
		if ($order_info['shipping_address_2']) {
			$query .= '&BillingAddress=' .  $order_info['shipping_address_1'] . "\r\n" . $order_info['shipping_address_2'] . "\r\n" . $order_info['shipping_city'] . "\r\n" . $order_info['shipping_zone'] . "\r\n";
		} else {
			$query .= '&BillingAddress=' .  $order_info['shipping_address_1'] . "\r\n" . $order_info['shipping_city'] . "\r\n" . $order_info['shipping_zone'] . "\r\n";
		}
		
		$query .= '&BillingPostCode=' . $order_info['shipping_postcode'];
		$query .= '&CustomerEMail=' . $order_info['email'];
		$query .= '&VendorEMail=' . $this->config->get('config_email');

		$data = array();

		for ($i = 0; $i < strlen(utf8_decode($password)); $i++) {
			$data[$i] = ord(substr($password, $i, 1));
		}

		$output = '';

		for ($i = 0; $i < strlen(utf8_decode($query)); $i++) {
    		$output .= chr(ord(substr($query, $i, 1)) ^ ($data[$i % strlen(utf8_decode($password))]));
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