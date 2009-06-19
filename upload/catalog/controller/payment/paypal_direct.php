<?php
class ControllerPaymentPayPalDirect extends Controller {
	protected function index() {
    	$this->language->load('payment/paypal_direct');
		
		$this->data['text_credit_card'] = $this->language->get('text_credit_card');
		$this->data['text_start_date'] = $this->language->get('text_start_date');
		$this->data['text_issue_number'] = $this->language->get('text_issue_number');
		
		$this->data['entry_credit_card_type'] = $this->language->get('entry_credit_card_type');
		$this->data['entry_credit_card_number'] = $this->language->get('entry_credit_card_number');
		$this->data['entry_start_date'] = $this->language->get('entry_start_date');
		$this->data['entry_expire_date'] = $this->language->get('entry_expire_date');
		$this->data['entry_cvv2_number'] = $this->language->get('entry_cvv2_number');
		$this->data['entry_issue_number'] = $this->language->get('entry_issue_number');
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		
		$this->data['cards'] = array();

		$this->data['cards'][] = array(
			'text'  => 'Visa', 
			'value' => 'VISA'
		);

		$this->data['cards'][] = array(
			'text'  => 'MasterCard', 
			'value' => 'MASTERCARD'
		);

		$this->data['cards'][] = array(
			'text'  => 'Discover Card', 
			'value' => 'DISCOVER'
		);
		
		$this->data['cards'][] = array(
			'text'  => 'American Express', 
			'value' => 'AMEX'
		);

		$this->data['cards'][] = array(
			'text'  => 'Maestro', 
			'value' => 'SWITCH'
		);
		
		$this->data['cards'][] = array(
			'text'  => 'Solo', 
			'value' => 'SOLO'
		);		
	
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

		for ($i = $today['year']; $i < $today['year'] + 10; $i++) {
			$this->data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)) 
			);
		}
		
		$this->data['back'] = $this->url->https('checkout/payment');
		
		$this->id       = 'payment';
		$this->template = $this->config->get('config_template') . 'payment/paypal_direct.tpl';
		
		$this->render();		
	}

	public function send() {
		if (!$this->config->get('paypal_direct_test')) {
			$api_endpoint = 'https://api-3t.paypal.com/nvp';
		} else {
			$api_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
		}
		
		if (!$this->config->get('paypal_direct_transaction')) {
			$payment_type = 'Authorization';	
		} else {
			$payment_type = 'Sale';
		}
		
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$payment_address = $this->customer->getAddress($this->session->data['payment_address_id']);
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $api_endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		
		$payment_data = array(
			'METHOD'         => 'DoDirectPayment', 
			'VERSION'        => '51.0', 
			'PWD'            => $this->config->get('paypal_direct_password'),
			'USER'           => $this->config->get('paypal_direct_username'),
			'SIGNATURE'      => $this->config->get('paypal_direct_signature'),
			'CUSTREF'        => $order_info['order_id'],
			'PAYMENTACTION'  => $payment_type,
			'AMT'            => $this->currency->format($order_info['total'], $order_info['currency'], 1.00000, FALSE),
			'CREDITCARDTYPE' => $this->request->post['credit_card_type'],
			'ACCT'           => str_replace(' ', '', $this->request->post['credit_card_number']),
			'CARDSTART'      => $this->request->post['start_date_month'] . $this->request->post['start_date_year'],
			'EXPDATE'        => $this->request->post['expire_date_month'] . $this->request->post['expire_date_year'],
			'CVV2'           => $this->request->post['cvv2_number'],
			'CARDISSUE'      => $this->request->post['issue_number'],
			'FIRSTNAME'      => $order_info['payment_firstname'],
			'LASTNAME'       => $order_info['payment_lastname'],
			'EMAIL'          => $order_info['email'],
			'PHONENUM'       => $order_info['telephone'],
			'IPADDRESS'      => $this->request->server['REMOTE_ADDR'],
			'STREET'         => $order_info['payment_address_1'],
			'CITY'           => $order_info['payment_city'],
			'STATE'          => $order_info['payment_zone'],
			'ZIP'            => $order_info['payment_postcode'],
			'COUNTRYCODE'    => $payment_address['iso_code_2'],
			'CURRENCYCODE'   => $order_info['currency']
		);
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payment_data));

		$response = curl_exec($ch);

		if (!$response) {
			exit('DoDirectPayment failed: ' . curl_error($ch) . '(' . curl_errno($ch) . ')');
		}
 
 		$response_data = array();
 
		parse_str($response, $response_data);

		$json = array();
		
		if ($response_data['ACK'] == 'Success') {
			$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
			
			$message = '';
			
			if (isset($response_data['AVSCODE'])) {
				$message .= 'AVSCODE: ' . $response_data['AVSCODE'] . "\n";
			}

			if (isset($response_data['CVV2MATCH'])) {
				$message .= 'CVV2MATCH: ' . $response_data['CVV2MATCH'] . "\n";
			}

			if (isset($response_data['TRANSACTIONID'])) {
				$message .= 'TRANSACTIONID: ' . $response_data['TRANSACTIONID'] . "\n";
			}
			
			$this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('paypal_direct_order_status_id'), $message, FALSE);
		
			$json['success'] = TRUE; 
		}
		
        if (($response_data['ACK'] != 'Success') && ($response_data['ACK'] != 'SuccessWithWarning')) {
        	$json['error'] = $response_data['L_LONGMESSAGE0'];
        }
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
	}
}
?>