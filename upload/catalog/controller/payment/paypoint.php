<?php
class ControllerPaymentPaypoint extends Controller {
	protected function index() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$this->data['action'] = 'https://www.secpay.com/java-bin/ValCard';
		
		$this->data['merchant'] = $this->config->get('paypoint_merchant');
		$this->data['trans_id'] = $this->session->data['order_id'];
		$this->data['amount'] = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
		$this->data['bill_name'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		$this->data['bill_addr_1'] = $order_info['payment_address_1'];
		$this->data['bill_addr_2'] = $order_info['payment_address_2'];
		$this->data['bill_city'] = $order_info['payment_city'];
		$this->data['bill_state'] = $order_info['payment_zone'];
		$this->data['bill_post_code'] = $order_info['payment_postcode'];
		$this->data['bill_country'] = $order_info['payment_country'];
		$this->data['bill_tel'] = $order_info['telephone'];
		$this->data['bill_email'] = $order_info['email'];
			
		if ($this->cart->hasShipping()) {
			$this->data['ship_name'] = $order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'];
			$this->data['ship_addr_1'] = $order_info['shipping_address_1'];
			$this->data['ship_addr_2'] = $order_info['shipping_address_2'];
			$this->data['ship_city'] = $order_info['shipping_city'];
			$this->data['ship_state'] = $order_info['shipping_zone'];
			$this->data['ship_post_code'] = $order_info['shipping_postcode'];
			$this->data['ship_country'] = $order_info['shipping_country'];
		} else {
			$this->data['ship_name'] = '';
			$this->data['ship_addr_1'] = '';
			$this->data['ship_addr_2'] = '';
			$this->data['ship_city'] = '';
			$this->data['ship_state'] = '';
			$this->data['ship_post_code'] = '';
			$this->data['ship_country'] = '';			
		}
		
		$this->data['currency'] = $this->currency->getCode();
		$this->data['callback'] = HTTPS_SERVER . 'index.php?route=payment/paypoint/callback';
		
		$this->load->library('encryption');
		
		$encryption = new Encryption($this->config->get('config_encryption'));
		
		$this->data['order_id'] = $encryption->encrypt($this->session->data['order_id']);
		
		switch ($this->config->get('paypoint_test')) {
			case 'production':
				$status = 'live';
				break;
			case 'successful':
			default:
				$status = 'true';
				break;
			case 'fail':
				$status = 'false';
				break;
		}
		
		$this->data['options'] = 'test_status=' . $status . ',dups=false,cb_flds=order_id';

		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}
		
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paypoint.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/paypoint.tpl';
		} else {
			$this->template = 'default/template/payment/paypoint.tpl';
		}	
		
		$this->render();
	}
	
	public function callback() {
		$this->language->load('payment/paypoint');
	
		$this->data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

		if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
			$this->data['base'] = HTTP_SERVER;
		} else {
			$this->data['base'] = HTTPS_SERVER;
		}
	
		$this->data['charset'] = $this->language->get('charset');
		$this->data['language'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
	
		$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
		
		$this->data['text_response'] = $this->language->get('text_response');
		$this->data['text_success'] = $this->language->get('text_success');
		$this->data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), HTTPS_SERVER . 'index.php?route=checkout/success');
		$this->data['text_failure'] = $this->language->get('text_failure');
		$this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), HTTPS_SERVER . 'index.php?route=checkout/cart');
	
		if (isset($this->request->get['valid']) && $this->request->get['valid'] == 'true') {
			$this->load->library('encryption');
			
			$encryption = new Encryption($this->config->get('config_encryption'));
		
			$order_id = $encryption->decrypt($this->request->get['order_id']);
	
			$this->load->model('checkout/order');
	
			$this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'));
	
			$message = '';
	
			if (isset($this->request->get['code'])) {
				$message .= 'code: ' . $this->request->get['code'] . "\n";
			}
		
			if (isset($this->request->get['auth_code'])) {
				$message .= 'auth_code: ' . $this->request->get['auth_code'] . "\n";
			}
		
			if (isset($this->request->get['ip'])) {
				$message .= 'ip: ' . $this->request->get['ip'] . "\n";
			}
		
			if (isset($this->request->get['cv2avs'])) {
				$message .= 'cv2avs: ' . $this->request->get['cv2avs'] . "\n";
			}	
	
			if (isset($this->request->get['trans_id'])) {
				$message .= 'trans_id: ' . $this->request->get['trans_id'] . "\n";
			}	
			
			if (isset($this->request->get['valid'])) {
				$message .= 'valid: ' . $this->request->get['valid'] . "\n";
			}
			
			$this->model_checkout_order->update($order_id, $this->config->get('paypoint_order_status_id'), $message, FALSE);
	
			$this->data['continue'] = HTTPS_SERVER . 'index.php?route=checkout/success';

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paypoint_success.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/payment/paypoint_success.tpl';
			} else {
				$this->template = 'default/template/payment/paypoint_success.tpl';
			}	
		
	  		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));			
		} else {
			$this->data['continue'] = HTTPS_SERVER . 'index.php?route=checkout/cart';
		
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paypoint_failure.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/payment/paypoint_failure.tpl';
			} else {
				$this->template = 'default/template/payment/paypoint_failure.tpl';
			}	
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		}
	}
}
?>