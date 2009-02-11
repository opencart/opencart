<?php
class ControllerPaymentGoogle extends Controller {
	protected function index() {
    	$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

		if (!$this->config->get('google_test')) {
    		$this->data['action'] = 'https://checkout.google.com/api/checkout/v2/checkoutForm/Merchant/';
  		} else {
			$this->data['action'] = 'https://sandbox.google.com/checkout/';
		}		
		 
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$this->data['business'] = $this->config->get('google_email');
		$this->data['item_name'] = $this->config->get('config_store');				
		$this->data['currency'] = $order_info['currency'];
		$this->data['amount'] = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
		$this->data['first_name'] = $order_info['payment_firstname'];
		$this->data['last_name'] = $order_info['payment_lastname'];
		$this->data['address1'] = $order_info['payment_address_1'];
		$this->data['address2'] = $order_info['payment_address_2'];
		$this->data['city'] = $order_info['payment_city'];
		$this->data['zip'] = $order_info['payment_postcode'];
		$this->data['country'] = $order_info['payment_country'];
		$this->data['notify_url'] = $this->url->https('payment/google/callback&order_id=' . $this->session->data['order_id']);
		$this->data['email'] = $order_info['email'];
		$this->data['invoice'] = date($this->language->get('date_format_short')) . ' - ' . $order_info['firstname'] . ' ' . $order_info['lastname'];
		$this->data['lc'] = $this->language->getCode();
		$this->data['return'] = $this->url->https('checkout/success');
		$this->data['cancel_return'] = $this->url->https('checkout/payment');

		$this->data['back'] = $this->url->https('checkout/payment');
		
		$this->id       = 'payment';
		$this->template = 'payment/google.tpl';
		
		$this->render();		
	}
	
	public function confirm() {
		$this->load->model('checkout/order');
		
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
	}
	
	public function callback() {
		$req = 'cmd=_notify-validate';
		
		foreach ($this->request->post as $key => $value) {
			$req .= '&' . $key . '=' . urlencode(stripslashes($value));
		}

		$header  = 'POST /cgi-bin/webscr HTTP/1.0' . "\r\n";
		$header .= 'Content-Type: application/x-www-form-urlencoded' . "\r\n";
		$header .= 'Content-Length: ' . strlen($req) . "\r\n\r\n";
		
		if (!$this->config->get('google_test')) {
			$fp = fsockopen('ssl://www.google.com', 443, $errno, $errstr, 30);
		} else {
			$fp = fsockopen('ssl://www.sandbox.google.com', 443, $errno, $errstr, 30);
		}
		
		if ($fp) {
			fputs($fp, $header . $req);
			
			while (!feof($fp)) {
				$res = fgets($fp, 1024);
				
				if (strcmp($res, 'VERIFIED') == 0) {
					$this->load->model('checkout/order');
					
					$this->model_checkout_order->update($this->request->get['order_id'], $this->config->get('google_order_status_id'));
				}
			}
			
			fclose($fp);
		}	
	}
}
?>