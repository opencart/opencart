<?php
class ControllerPaymentPerpetualPayments extends Controller {
	protected function index() {
    	$this->language->load('payment/perpetual_payments');
		
		$this->data['text_credit_card'] = $this->language->get('text_credit_card');
		$this->data['text_start_date'] = $this->language->get('text_start_date');
		$this->data['text_issue'] = $this->language->get('text_issue');
		$this->data['text_wait'] = $this->language->get('text_wait');
		
		$this->data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$this->data['entry_cc_start_date'] = $this->language->get('entry_cc_start_date');
		$this->data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$this->data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
		$this->data['entry_cc_issue'] = $this->language->get('entry_cc_issue');
		
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
		
		$this->data['year_valid'] = array();
		
		for ($i = $today['year'] - 10; $i < $today['year'] + 1; $i++) {	
			$this->data['year_valid'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)), 
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}

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

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/perpetual_payments.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/perpetual_payments.tpl';
		} else {
			$this->template = 'default/template/payment/perpetual_payments.tpl';
		}	
			
		$this->render();		
	}

	public function send() {
		$this->language->load('payment/perpetual_payments');
		
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$payment_data = array(
			'auth_id'        => $this->config->get('perpetual_payments_auth_id'),
			'auth_pass'      => $this->config->get('perpetual_payments_auth_pass'),
			'card_num'       => str_replace(' ', '', $this->request->post['cc_number']),
			'card_cvv'       => $this->request->post['cc_cvv2'],
			'card_start'     => $this->request->post['cc_start_date_month'] . substr($this->request->post['cc_start_date_year'], 2),
			'card_expiry'    => $this->request->post['cc_expire_date_month'] . substr($this->request->post['cc_expire_date_year'], 2),
			'cust_name'      => $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'],
			'cust_address'   => $order_info['payment_address_1'] . ' ' . $order_info['payment_city'],
			'cust_country'   => $order_info['payment_iso_code_2'],
			'cust_postcode'	 => $order_info['payment_postcode'],
			'cust_tel'	 	 => $order_info['telephone'],
			'cust_ip'        => $this->request->server['REMOTE_ADDR'],
			'cust_email'     => $order_info['email'],
			'tran_ref'       => $order_info['order_id'],
			'tran_amount'    => $this->currency->format($order_info['total'], $order_info['currency'], 1.00000, FALSE),
			'tran_currency' => $order_info['currency'],
			'tran_testmode' => $this->config->get('perpetual_payments_test'),
			'tran_type'     => 'Sale',
			'tran_class'    => 'MoTo',
		);

		$curl = curl_init('https://secure.voice-pay.com/gateway/remote');
		
		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($payment_data));

		$response = curl_exec($curl);
 		
		curl_close($curl);
		
		if ($response) {
			$data = explode('|', $response);
			
			if (isset($data[0]) && $data[0] == 'A') {
				$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
				
				$message = '';
				
				if (isset($data[1])) {
					$message .= $this->language->get('text_transaction') . ' ' . $data[1] . "\n";
				}
				
				if (isset($data[2])) {
					if ($data[2] == '232') {
						$message .= $this->language->get('text_avs') . ' ' . $this->language->get('text_avs_full_match') . "\n";
					} elseif ($data[2] == '400') {
						$message .= $this->language->get('text_avs') . ' ' . $this->language->get('text_avs_not_match') . "\n";
					}
				}
				
				if (isset($data[3])) {
					$message .= $this->language->get('text_authorisation') . ' ' . $data[3] . "\n";
				}
				
				$this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('perpetual_payments_order_status_id'), $message, FALSE);
					
				$json['success'] = HTTPS_SERVER . 'index.php?route=checkout/success';
			} else {
				$json['error'] = end($data);
			}
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
	}
}
?>