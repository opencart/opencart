<?php
class ControllerPaymentPayPal extends Controller {
	protected function index() {
    	$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

		if (!$this->config->get('paypal_test')) {
    		$this->data['action'] = 'https://www.paypal.com/cgi-bin/webscr';
  		} else {
			$this->data['action'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}		
		
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$this->load->library('encryption');
		
		$encryption = new Encryption($this->config->get('paypal_encryption'));
																				   
		$this->data['business'] = $this->config->get('paypal_email');
		$this->data['item_name'] = $this->config->get('config_store');				
		$this->data['currency_code'] = $order_info['currency'];
		$this->data['amount'] = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
		$this->data['first_name'] = $order_info['payment_firstname'];
		$this->data['last_name'] = $order_info['payment_lastname'];
		$this->data['address1'] = $order_info['payment_address_1'];
		$this->data['address2'] = $order_info['payment_address_2'];
		$this->data['city'] = $order_info['payment_city'];
		$this->data['zip'] = $order_info['payment_postcode'];
		$this->data['country'] = $order_info['payment_country'];
		$this->data['notify_url'] = $this->url->https('payment/paypal/callback&order_id=' . $encryption->encrypt($this->session->data['order_id']));
		$this->data['email'] = $order_info['email'];
		$this->data['invoice'] = $this->session->data['order_id'] . ' - ' . $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		$this->data['lc'] = $this->language->getCode();
		$this->data['return'] = $this->url->https('checkout/success');
		$this->data['cancel_return'] = $this->url->https('checkout/payment');

		$this->data['back'] = $this->url->https('checkout/payment');
		
		$this->id       = 'payment';
		$this->template = $this->config->get('config_template') . 'payment/paypal.tpl';
		
		$this->render();		
	}
	
	public function confirm() {
		$this->load->model('checkout/order');
		
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
	}
	
	public function callback() {
		$this->load->library('encryption');
		
		$encryption = new Encryption($this->config->get('paypal_encryption'));
		$order_id = $encryption->decrypt(@$this->request->get['order_id']);
		
		$this->load->model('checkout/order');
				
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		if ($order_info) {
			$req = 'cmd=_notify-validate';
		
			foreach ($this->request->post as $key => $value) {
				$req .= '&' . $key . '=' . urlencode(stripslashes($value));
			}

			$header  = 'POST /cgi-bin/webscr HTTP/1.0' . "\r\n";
			$header .= 'Content-Type: application/x-www-form-urlencoded' . "\r\n";
			$header .= 'Content-Length: ' . strlen($req) . "\r\n\r\n";
		
			if (!$this->config->get('paypal_test')) {
				$fp = fsockopen('www.paypal.com', 80, $errno, $errstr, 30);
			} else {
				$fp = fsockopen('www.sandbox.paypal.com', 80, $errno, $errstr, 30);
			}
		
			if ($fp) {
				fputs($fp, $header . $req);
			
				while (!feof($fp)) {
					$res = fgets($fp, 1024);
				
					if (strcmp($res, 'VERIFIED') == 0) {
						$this->model_checkout_order->update($order_id, $this->config->get('paypal_order_status_id'));
					}
				}
			
				fclose($fp);
			}
		}
	}
}
?>