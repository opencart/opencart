<?php
class ControllerPaymentSagepayDirect extends Controller {
	protected function index() {
		$this->language->load('payment/sagepay_direct');

		$this->data['text_credit_card'] = $this->language->get('text_credit_card');
		$this->data['text_start_date'] = $this->language->get('text_start_date');
		$this->data['text_issue'] = $this->language->get('text_issue');
		$this->data['text_wait'] = $this->language->get('text_wait');

		$this->data['entry_cc_owner'] = $this->language->get('entry_cc_owner');
		$this->data['entry_cc_type'] = $this->language->get('entry_cc_type');
		$this->data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$this->data['entry_cc_start_date'] = $this->language->get('entry_cc_start_date');
		$this->data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$this->data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
		$this->data['entry_cc_issue'] = $this->language->get('entry_cc_issue');

		$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->data['cards'] = array();

		$this->data['cards'][] = array(
			'text'  => 'Visa', 
			'value' => 'VISA'
		);

		$this->data['cards'][] = array(
			'text'  => 'MasterCard', 
			'value' => 'MC'
		);

		$this->data['cards'][] = array(
			'text'  => 'Visa Delta/Debit', 
			'value' => 'DELTA'
		);

		$this->data['cards'][] = array(
			'text'  => 'Solo', 
			'value' => 'SOLO'
		);	

		$this->data['cards'][] = array(
			'text'  => 'Maestro', 
			'value' => 'MAESTRO'
		);

		$this->data['cards'][] = array(
			'text'  => 'Visa Electron UK Debit', 
			'value' => 'UKE'
		);

		$this->data['cards'][] = array(
			'text'  => 'American Express', 
			'value' => 'AMEX'
		);

		$this->data['cards'][] = array(
			'text'  => 'Diners Club', 
			'value' => 'DC'
		);

		$this->data['cards'][] = array(
			'text'  => 'Japan Credit Bureau', 
			'value' => 'JCB'
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

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$this->data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)) 
			);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/sagepay_direct.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/sagepay_direct.tpl';
		} else {
			$this->template = 'default/template/payment/sagepay_direct.tpl';
		}	

		$this->render();		
	}

	public function send() {
		if ($this->config->get('sagepay_direct_test') == 'live') {
			$url = 'https://live.sagepay.com/gateway/service/vspdirect-register.vsp';
		} elseif ($this->config->get('sagepay_direct_test') == 'test') {
			$url = 'https://test.sagepay.com/gateway/service/vspdirect-register.vsp';		
		} elseif ($this->config->get('sagepay_direct_test') == 'sim') {
			$url = 'https://test.sagepay.com/Simulator/VSPDirectGateway.asp';
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$data = array();

		$data['VPSProtocol'] = '2.23';
		$data['ReferrerID'] = 'E511AF91-E4A0-42DE-80B0-09C981A3FB61';
		$data['Vendor'] = $this->config->get('sagepay_direct_vendor');
		$data['VendorTxCode'] = $this->session->data['order_id'];
		$data['Amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, false);
		$data['Currency'] = $this->currency->getCode();
		$data['Description'] = substr($this->config->get('config_name'), 0, 100);
		$data['CardHolder'] = $this->request->post['cc_owner'];
		$data['CardNumber'] = $this->request->post['cc_number'];
		$data['ExpiryDate'] = $this->request->post['cc_expire_date_month'] . substr($this->request->post['cc_expire_date_year'], 2);
		$data['CardType'] = $this->request->post['cc_type'];
		$data['TxType'] = $this->config->get('sagepay_direct_transaction');
		$data['StartDate'] = $this->request->post['cc_start_date_month'] . substr($this->request->post['cc_start_date_year'], 2);
		$data['IssueNumber'] = $this->request->post['cc_issue'];
		$data['CV2'] = $this->request->post['cc_cvv2'];

		$data['BillingSurname'] = substr($order_info['payment_lastname'], 0, 20);
		$data['BillingFirstnames'] = substr($order_info['payment_firstname'], 0, 20);
		$data['BillingAddress1'] = substr($order_info['payment_address_1'], 0, 100);

		if ($order_info['payment_address_2']) {
			$data['BillingAddress2'] = $order_info['payment_address_2'];
		}

		$data['BillingCity'] = substr($order_info['payment_city'], 0, 40);
		$data['BillingPostCode'] = substr($order_info['payment_postcode'], 0, 10);
		$data['BillingCountry'] = $order_info['payment_iso_code_2'];

		if ($order_info['payment_iso_code_2'] == 'US') {
			$data['BillingState'] = $order_info['payment_zone_code'];
		}

		$data['BillingPhone'] = substr($order_info['telephone'], 0, 20);

		if ($this->cart->hasShipping()) {
			$data['DeliverySurname'] = substr($order_info['shipping_lastname'], 0, 20);
			$data['DeliveryFirstnames'] = substr($order_info['shipping_firstname'], 0, 20);
			$data['DeliveryAddress1'] = substr($order_info['shipping_address_1'], 0, 100);

			if ($order_info['shipping_address_2']) {
				$data['DeliveryAddress2'] = $order_info['shipping_address_2'];
			}		

			$data['DeliveryCity'] = substr($order_info['shipping_city'], 0, 40);
			$data['DeliveryPostCode'] = substr($order_info['shipping_postcode'], 0, 10);
			$data['DeliveryCountry'] = $order_info['shipping_iso_code_2'];

			if ($order_info['shipping_iso_code_2'] == 'US') {
				$data['DeliveryState'] = $order_info['shipping_zone_code'];
			}

			$data['CustomerName'] = substr($order_info['firstname'] . ' ' . $order_info['lastname'], 0, 100);
			$data['DeliveryPhone'] = substr($order_info['telephone'], 0, 20);
		} else {
			$data['DeliveryFirstnames'] = $order_info['payment_firstname'];
			$data['DeliverySurname'] = $order_info['payment_lastname'];
			$data['DeliveryAddress1'] = $order_info['payment_address_1'];

			if ($order_info['payment_address_2']) {
				$data['DeliveryAddress2'] = $order_info['payment_address_2'];
			}

			$data['DeliveryCity'] = $order_info['payment_city'];
			$data['DeliveryPostCode'] = $order_info['payment_postcode'];
			$data['DeliveryCountry'] = $order_info['payment_iso_code_2'];

			if ($order_info['payment_iso_code_2'] == 'US') {
				$data['DeliveryState'] = $order_info['payment_zone_code'];
			}

			$data['DeliveryPhone'] = $order_info['telephone'];			
		}		

		$data['CustomerEMail'] = substr($order_info['email'], 0, 255);
		$data['Apply3DSecure'] = '0';
		$data['ClientIPAddress'] = $this->request->server['REMOTE_ADDR'];

		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

		$response = curl_exec($curl);

		curl_close($curl);

		$data = array();

		$response_info = explode(chr(10), $response);

		foreach ($response_info as $string) {
			if (strpos($string, '=')) {
				$parts = explode('=', $string, 2);

				$data[trim($parts[0])] = trim($parts[1]);
			}
		}

		$json = array();

		if ($data['Status'] == '3DAUTH') {
			$json['ACSURL'] = $data['ACSURL'];
			$json['MD'] = $data['MD'];
			$json['PaReq'] = $data['PAReq'];
			$json['TermUrl'] = $this->url->link('payment/sagepay_direct/callback');
		} elseif ($data['Status'] == 'OK' || $data['Status'] == 'AUTHENTICATED' || $data['Status'] == 'REGISTERED') {
			$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));

			$message = '';

			if (isset($data['TxAuthNo'])) {
				$message .= 'TxAuthNo: ' . $data['TxAuthNo'] . "\n";
			}

			if (isset($data['AVSCV2'])) {
				$message .= 'AVSCV2: ' . $data['AVSCV2'] . "\n";
			}

			if (isset($data['AddressResult'])) {
				$message .= 'AddressResult: ' . $data['AddressResult'] . "\n";
			}

			if (isset($data['PostCodeResult'])) {
				$message .= 'PostCodeResult: ' . $data['PostCodeResult'] . "\n";
			}

			if (isset($data['CV2Result'])) {
				$message .= 'CV2Result: ' . $data['CV2Result'] . "\n";
			}

			if (isset($data['3DSecureStatus'])) {
				$message .= '3DSecureStatus: ' . $data['3DSecureStatus'] . "\n";
			}

			if (isset($data['CAVV'])) {
				$message .= 'CAVV: ' . $data['CAVV'] . "\n";
			}

			$this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('sagepay_direct_order_status_id'), $message, false);

			$json['success'] = $this->url->link('checkout/success'); 			
		} else {
			$json['error'] = $data['StatusDetail'];
		}

		$this->response->setOutput(json_encode($json));
	}	 

	public function callback() {
		if (isset($this->session->data['order_id'])) {
			if ($this->config->get('sagepay_direct_test') == 'live') {
				$url = 'https://live.sagepay.com/gateway/service/direct3dcallback.vsp';
			} elseif ($this->config->get('sagepay_direct_test') == 'test') {
				$url = 'https://test.sagepay.com/gateway/service/direct3dcallback.vsp';		
			} elseif ($this->config->get('sagepay_direct_test') == 'sim') {
				$url = 'https://test.sagepay.com/Simulator/VSPDirectCallback.asp';
			} 	

			$curl = curl_init($url);

			curl_setopt($curl, CURLOPT_PORT, 443);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->request->post));

			$response = curl_exec($curl);

			curl_close($curl);

			$data = array();

			$response_info = explode(chr(10), $response);

			foreach ($response_info as $string) {
				if (strpos($string, '=')) {
					$parts = explode('=', $string, 2);

					$data[trim($parts[0])] = trim($parts[1]);
				}
			}

			if ($data['Status'] == 'OK' || $data['Status'] == 'AUTHENTICATED' || $data['Status'] == 'REGISTERED') {
				$this->load->model('checkout/order');

				$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));

				$message = '';

				if (isset($data['TxAuthNo'])) {
					$message .= 'TxAuthNo: ' . $data['TxAuthNo'] . "\n";
				}

				if (isset($data['AVSCV2'])) {
					$message .= 'AVSCV2: ' . $data['AVSCV2'] . "\n";
				}

				if (isset($data['AddressResult'])) {
					$message .= 'AddressResult: ' . $data['AddressResult'] . "\n";
				}

				if (isset($data['PostCodeResult'])) {
					$message .= 'PostCodeResult: ' . $data['PostCodeResult'] . "\n";
				}

				if (isset($data['CV2Result'])) {
					$message .= 'CV2Result: ' . $data['CV2Result'] . "\n";
				}

				if (isset($data['3DSecureStatus'])) {
					$message .= '3DSecureStatus: ' . $data['3DSecureStatus'] . "\n";
				}

				if (isset($data['CAVV'])) {
					$message .= 'CAVV: ' . $data['CAVV'] . "\n";
				}

				$this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('sagepay_direct_order_status_id'), $message, false);	

				$this->redirect($this->url->link('checkout/success'));
			} else {
				$this->session->data['error'] = $data['StatusDetail'];

				$this->redirect($this->url->link('checkout/checkout', '', 'SSL'));
			}
		} else {
			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}
	}
}
?>