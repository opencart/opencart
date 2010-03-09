<?php
class ControllerPaymentSagepayUS extends Controller {
	protected function index() {
    	$this->language->load('payment/sagepay_us');
		
		$this->data['text_credit_card'] = $this->language->get('text_credit_card');
		$this->data['text_wait'] = $this->language->get('text_wait');
		
		$this->data['entry_cc_owner'] = $this->language->get('entry_cc_owner');
		$this->data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$this->data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$this->data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		
		$this->data['months'] = array();
		
		for ($i = 1; $i <= 12; $i++) {
			$this->data['months'][] = array(
				'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)), 
				'value' => sprintf('%02d', $i)
			);
		}
		
		$today = getdate();

		$this->data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$this->data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)) 
			);
		}
		
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}
		
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/sagepay_us.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/sagepay_us.tpl';
		} else {
			$this->template = 'default/template/payment/sagepay_us.tpl';
		}	
		
		$this->render();		
	}
	
	public function send() {
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$url = 'https://www.sagepayments.net/cgi-bin/eftbankcard.dll?transaction';
		
		$data  = 'm_id=' . $this->config->get('sagepay_us_merchant_id');
		$data .= '&m_key=' . $this->config->get('sagepay_us_merchant_key');
		$data .= '&T_amt=' . urlencode($this->currency->format($order_info['total'], $order_info['currency'], 1.00000, FALSE));
		$data .= '&T_ordernum=' . $this->session->data['order_id'];
		$data .= '&C_name=' . urlencode($this->request->post['cc_owner']);
		$data .= '&C_address=' . urlencode($order_info['payment_address_1']);
		$data .= '&C_state=' . urlencode($order_info['payment_zone']);
		$data .= '&C_city=' . urlencode($order_info['payment_city']);
		$data .= '&C_cardnumber=' . urlencode($this->request->post['cc_number']);
		$data .= '&C_exp=' . urlencode($this->request->post['cc_expire_date_month'] . substr($this->request->post['cc_expire_date_year'], '2'));
		$data .= '&C_cvv=' . urlencode($this->request->post['cc_cvv2']);
		$data .= '&C_zip=' . urlencode($order_info['payment_postcode']);
		$data .= '&C_email=' . urlencode($order_info['email']);
		$data .= '&T_code=02';
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec($ch);
		
		curl_close($ch);

		$json = array();
															
		if ($response[1] == 'A') {
			$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));

			$message  = 'Approval Indicator: ' . $response[1] . "\n";
			$message .= 'Approval/Error Code: ' . substr($response, 2, 6) . "\n";
			$message .= 'Approval/Error Message: ' . substr($response, 8, 32) . "\n";
			$message .= 'Front-End Indicator: ' . substr($response, 40, 2) . "\n";
			$message .= 'CVV Indicator: ' . $response[42] . "\n";
			$message .= 'AVS Indicator: ' . $response[43] . "\n";
			$message .= 'Risk Indicator: ' . substr($response, 44, 2) . "\n";
			$message .= 'Reference: ' . substr($response, 46, 10) . "\n";
			$message .= 'Order Number: ' . substr($response, strpos($response, chr(28)) + 1, strrpos($response, chr(28) - 1)) . "\n";
			
			$this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('sagepay_us_order_status_id'), $message, FALSE);

			$json['success'] = HTTPS_SERVER . 'index.php?route=checkout/success';
		} else {
			$json['error'] = substr($response, 8, 32);
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));		
	}	
}
?>