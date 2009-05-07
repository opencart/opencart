<?php
class ControllerPaymentWorldPay extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$this->data['action'] = 'https://select.worldpay.com/wcc/purchase';

		$this->data['merchant'] = $this->config->get('worldpay_merchant');
		$this->data['order_id'] = $order_info['order_id'];
		$this->data['amount'] = $order_info['total'];
		$this->data['currency'] = $order_info['currency'];
		$this->data['description'] = $this->config->get('config_store') . ' - #' . $order_info['order_id'];
		$this->data['name'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		
		if (!$order_info['payment_address_2']) {
			$this->data['address'] = $order_info['payment_address_1'] . ', ' . $order_info['payment_city'] . ', ' . $order_info['payment_zone'];
		} else {
			$this->data['address'] = $order_info['payment_address_1'] . ', ' . $order_info['payment_address_2'] . ', ' . $order_info['payment_city'] . ', ' . $order_info['payment_zone'];
		}
		
		$this->data['postcode'] = $order_info['payment_postcode'];
		$this->data['country'] = $order_info['payment_country'];
		$this->data['telephone'] = $order_info['telephone'];
		$this->data['email'] = $order_info['email'];
		$this->data['test'] = $this->config->get('worldpay_test');
		
		$this->data['back'] = $this->url->https('checkout/payment');
		
		$this->id       = 'payment';
		$this->template = $this->config->get('config_template') . 'payment/worldpay.tpl';
		
		$this->render();
	}
	
	public function confirm() {
		$this->load->model('checkout/order');
		
		$this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('config_order_status_id'));
	}
	
	public function callback() {
		$this->load->language('payment/worldpay');
		
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'payment/worldpay_callback.tpl';
		$this->layout   = 'module/layout';
			  
	  	$this->render();
	}
}
?>