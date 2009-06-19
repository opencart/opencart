<?php
class ControllerPaymentMoneybookers extends Controller {
	protected function index() {
		$this->load->model('checkout/order');
		$this->language->load('payment/moneybookers');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$this->load->model('localisation/country');
		
		$country_info = $this->model_localisation_country->getCountry($this->session->data['payment_address_id']);

		$products = '';
		
		foreach ($this->cart->getProducts() as $product) {
    		$products .= $product['quantity'] . ' x ' . $product['name'] . ', ';
    	}

    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back']	   = $this->language->get('button_back');
        
		$this->data['action'] = 'https://www.moneybookers.com/app/payment.pl?rid=10111486';
		$this->data['storelogourl'] = HTTP_IMAGE . $this->config->get('config_logo');
		$this->data['language'] = $this->language->get('code');					
		$this->data['recipient_desc'] = $this->config->get('config_store');
		$this->data['detail1_desc'] = $this->language->get('detail1_desc');
		$this->data['detail1_text'] = $products;
		$this->data['trans_id'] = $this->session->data['order_id'];
        $this->data['mb_email'] = $this->config->get('moneybookers_email');
        $this->data['amount'] = $this->currency->format($this->order_info['total'], $this->order_info['currency'], $this->order_info['value'], FALSE);
        $this->data['currency'] = $order_info['currency'];
        $this->data['order_id'] = $this->session->data['order_id'];
        $this->data['cust_firstname'] = $order_info['payment_firstname'];
        $this->data['cust_lastname'] = $order_info['payment_lastname'];
        $this->data['cust_address1'] = $this->order_info['shipping_address_1'];
        $this->data['cust_address2'] = $this->order_info['shipping_address_2'];
        $this->data['cust_postcode'] = $this->order_info['shipping_postcode'];
        $this->data['cust_city'] = $this->order_info['shipping_city'];
        $this->data['cust_zone'] = $order_info['shipping_zone'];
		$this->data['cust_country'] = $country_info['iso_code_3'];
        $this->data['cust_email'] = $order_info['email'];
        $this->data['return_url'] = $this->url->https('checkout/success');
        $this->data['cancel_url'] = $this->url->https('checkout/payment');
        $this->data['mb_note'] = $this->config->get('moneybookers_custnote');
        
		$this->data['back']	= $this->data['cancel_url'];
		
		$this->id       = 'payment';
		$this->template = $this->config->get('config_template') . 'payment/moneybookers.tpl';
		$this->render();
	}
	
	public function confirm() {
		$this->load->model('checkout/order');
		
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
	}
}
?>