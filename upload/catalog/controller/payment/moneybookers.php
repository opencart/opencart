<?php
class ControllerPaymentMoneybookers extends Controller {
	protected function index() {
		$this->load->model('checkout/order');
		
		$this->language->load('payment/moneybookers');
		
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
        
		$this->data['action'] = 'https://www.moneybookers.com/app/payment.pl?rid=10111486';
		
		$this->data['pay_to_email'] = $this->config->get('moneybookers_email');
		$this->data['description'] = $this->config->get('config_store');
		$this->data['transaction_id'] = $this->session->data['order_id'];
        $this->data['return_url'] = $this->url->https('checkout/success');
        $this->data['cancel_url'] = $this->url->https('checkout/payment');	
		$this->data['status_url'] = $this->url->https('payment/moneybookers/callback');
		$this->data['language'] = $this->language->getCode();		
		$this->data['logo'] = HTTP_IMAGE . $this->config->get('config_logo');
		
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$this->data['pay_from_email'] = $order_info['email'];
		$this->data['firstname'] = $order_info['payment_firstname'];
        $this->data['lastname'] = $order_info['payment_lastname'];
        $this->data['address'] = $order_info['payment_address_1'];
        $this->data['address2'] = $order_info['payment_address_2'];
        $this->data['phone_number'] = $order_info['telephone'];
		$this->data['postal_code'] = $order_info['payment_postcode'];
        $this->data['city'] = $order_info['payment_city'];
        $this->data['state'] = $order_info['payment_zone'];
		
		$payment_address = $this->customer->getAddress($this->session->data['payment_address_id']);
		
		$this->data['country'] = $payment_address['iso_code_3'];
        
		$this->data['amount'] = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
        $this->data['currency'] = $order_info['currency'];
		
		$products = '';
		
		foreach ($this->cart->getProducts() as $product) {
    		$products .= $product['quantity'] . ' x ' . $product['name'] . ', ';
    	}		
		
		$this->data['detail1_text'] = $products;
		
		$this->load->library('encryption');
		
		$encryption = new Encryption($this->config->get('config_encryption'));
		
		$this->data['order_id'] = $encryption->encrypt($this->session->data['order_id']);
		
		$this->data['back']	= $this->data['cancel_url'];
		
		$this->id       = 'payment';
		$this->template = $this->config->get('config_template') . 'payment/moneybookers.tpl';
		$this->render();
	}
	
	public function callback() {
		$this->load->library('encryption');
		
		$encryption = new Encryption($this->config->get('config_encryption'));
		
		$order_id = $encryption->decrypt(@$this->request->post['order_id']);
		
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		if ($order_info) {
			$this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'));
			
			switch($this->request->post['status']) {
				case '2':
					$this->model_checkout_order->update($order_id, $this->config->get('moneybookers_order_status_id'), '', TRUE);
					break;
				case '0':	
					$this->model_checkout_order->update($order_id, $this->config->get('moneybookers_order_status_pending_id'), '', TRUE);		
					break;
				case '-1':
					$this->model_checkout_order->update($order_id, $this->config->get('moneybookers_order_status_canceled_id'), '', TRUE);
					break;
				case '-2':
					$this->model_checkout_order->update($order_id, $this->config->get('moneybookers_order_status_failed_id'), '', TRUE);
					break;					
				case '-3':
					$this->model_checkout_order->update($order_id, $this->config->get('moneybookers_order_status_chargeback_id'), '', TRUE);
					break;
			}
		}
	}
}
?>