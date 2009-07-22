<?php
class ControllerPaymentPPStandard extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		if (!$this->config->get('pp_standard_test')) {
    		$this->data['action'] = 'https://www.paypal.com/cgi-bin/webscr';
  		} else {
			$this->data['action'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}		
		
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$this->load->library('encryption');
		
		$encryption = new Encryption($this->config->get('config_encryption'));
																				   
		$this->data['business'] = $this->config->get('pp_standard_email');
		$this->data['item_name'] = html_entity_decode($this->config->get('config_store'));				
		$this->data['currency_code'] = $order_info['currency'];
		$this->data['amount'] = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
		$this->data['first_name'] = $order_info['payment_firstname'];
		$this->data['last_name'] = $order_info['payment_lastname'];
		$this->data['address1'] = $order_info['payment_address_1'];
		$this->data['address2'] = $order_info['payment_address_2'];
		$this->data['city'] = $order_info['payment_city'];
		$this->data['zip'] = $order_info['payment_postcode'];
		
		$payment_address = $this->customer->getAddress($this->session->data['payment_address_id']);
		
		$this->data['country'] = $payment_address['iso_code_2'];
		
		$this->data['notify_url'] = $this->url->http('payment/pp_standard/callback&order_id=' . $encryption->encrypt($this->session->data['order_id']));
		$this->data['email'] = $order_info['email'];
		$this->data['invoice'] = $this->session->data['order_id'] . ' - ' . $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		$this->data['lc'] = $this->language->getCode();
		
		if (!$this->config->get('pp_standard_transaction')) {
			$this->data['paymentaction'] = 'authorization';
		} else {
			$this->data['paymentaction'] = 'sale';
		}
		
		$this->data['return'] = $this->url->https('checkout/success');
		$this->data['cancel_return'] = $this->url->https('checkout/payment');

		$this->data['back'] = $this->url->https('checkout/payment');
		
		$this->id       = 'payment';
		$this->template = $this->config->get('config_template') . 'payment/pp_standard.tpl';
		
		$this->render();		
	}
	
	public function callback() {
		$this->load->library('encryption');
		
		$encryption = new Encryption($this->config->get('config_encryption'));
		
		$order_id = $encryption->decrypt(@$this->request->get['order_id']);
		
		$this->load->model('checkout/order');
				
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		if ($order_info) {
			$request = 'cmd=_notify-validate';
		
			foreach ($this->request->post as $key => $value) {
				$request .= '&' . $key . '=' . urlencode(stripslashes($value));
			}

			$header  = 'POST /cgi-bin/webscr HTTP/1.0' . "\r\n";
			$header .= 'Content-Type: application/x-www-form-urlencoded' . "\r\n";
			$header .= 'Content-Length: ' . strlen(utf8_decode($request)) . "\r\n\r\n";
		
			if (!$this->config->get('pp_standard_test')) {
				$fp = fsockopen('www.paypal.com', 80, $errno, $errstr, 30);
			} else {
				$fp = fsockopen('www.sandbox.paypal.com', 80, $errno, $errstr, 30);
			}
		
			if ($fp) {
				fputs($fp, $header . $request);
			
				while (!feof($fp)) {
					$response = fgets($fp, 1024);
				
					if (strcmp($response, 'VERIFIED') == 0) {
						$this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'));
						
						switch($this->request->post['payment_status']){
							case 'Completed':
								$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_id'), '', TRUE);
								break;
							case 'Canceled_Reversal':
								$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_canceled_reversal_id'), '', TRUE);
								break;
							case 'Denied':
								$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_denied_id'), '', TRUE);
								break;
							case 'Failed':
								$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_failed_id'), '', TRUE);
								break;
							case 'Pending':
								$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_pending_id'), '', TRUE);
								break;
							case 'Refunded':
								$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_refunded_id'), '', TRUE);
								break;
							case 'Reversed':
								$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_reversed_id'), '', TRUE);
								break;
							default:
								$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_unspecified_id'), '', TRUE);
								break;
						}
					}
				}
			
				fclose($fp);
			}
		}
	}
}
?>