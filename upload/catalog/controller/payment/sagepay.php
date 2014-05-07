<?php
class ControllerPaymentSagepay extends Controller {
	public function index() {
		$this->load->language('payment/sagepay');

		$data['button_confirm'] = $this->language->get('button_confirm');

		if ($this->config->get('sagepay_test') == 'live') {
			$data['action'] = 'https://live.sagepay.com/gateway/service/vspform-register.vsp';
		} elseif ($this->config->get('sagepay_test') == 'test') {
			$data['action'] = 'https://test.sagepay.com/gateway/service/vspform-register.vsp';		
		} elseif ($this->config->get('sagepay_test') == 'sim') {
			$data['action'] = 'https://test.sagepay.com/simulator/vspformgateway.asp';
		}

		$vendor = $this->config->get('sagepay_vendor');
		$password = $this->config->get('sagepay_password');		

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$payment_data = array();

		$payment_data['VendorTxCode'] = $this->session->data['order_id'];
		$payment_data['ReferrerID'] = 'E511AF91-E4A0-42DE-80B0-09C981A3FB61';
		$payment_data['Amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$payment_data['Currency'] = $order_info['currency_code'];
		$payment_data['Description'] = sprintf($this->language->get('text_description'), date($this->language->get('date_format_short')), $this->session->data['order_id']);
		$payment_data['SuccessURL'] = str_replace('&amp;', '&', $this->url->link('payment/sagepay/success', 'order_id=' . $this->session->data['order_id']));
		$payment_data['FailureURL'] = str_replace('&amp;', '&', $this->url->link('checkout/checkout', '', 'SSL'));

		$payment_data['CustomerName'] = html_entity_decode($order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
		$payment_data['SendEMail'] = '1';
		$payment_data['CustomerEMail'] = $order_info['email'];
		$payment_data['VendorEMail'] = $this->config->get('config_email');  

		$payment_data['BillingFirstnames'] = $order_info['payment_firstname'];
		$payment_data['BillingSurname'] = $order_info['payment_lastname'];
		$payment_data['BillingAddress1'] = $order_info['payment_address_1'];

		if ($order_info['payment_address_2']) {
			$payment_data['BillingAddress2'] = $order_info['payment_address_2'];
		}

		$payment_data['BillingCity'] = $order_info['payment_city'];
		$payment_data['BillingPostCode'] = $order_info['payment_postcode'];
		$payment_data['BillingCountry'] = $order_info['payment_iso_code_2'];

		if ($order_info['payment_iso_code_2'] == 'US') {
			$payment_data['BillingState'] = $order_info['payment_zone_code'];
		}

		$payment_data['BillingPhone'] = $order_info['telephone'];

		if ($this->cart->hasShipping()) {
			$payment_data['DeliveryFirstnames'] = $order_info['shipping_firstname'];
			$payment_data['DeliverySurname'] = $order_info['shipping_lastname'];
			$payment_data['DeliveryAddress1'] = $order_info['shipping_address_1'];

			if ($order_info['shipping_address_2']) {
				$payment_data['DeliveryAddress2'] = $order_info['shipping_address_2'];
			}

			$payment_data['DeliveryCity'] = $order_info['shipping_city'];
			$payment_data['DeliveryPostCode'] = $order_info['shipping_postcode'];
			$payment_data['DeliveryCountry'] = $order_info['shipping_iso_code_2'];

			if ($order_info['shipping_iso_code_2'] == 'US') {
				$payment_data['DeliveryState'] = $order_info['shipping_zone_code'];
			}

			$payment_data['DeliveryPhone'] = $order_info['telephone'];
		} else {
			$payment_data['DeliveryFirstnames'] = $order_info['payment_firstname'];
			$payment_data['DeliverySurname'] = $order_info['payment_lastname'];
			$payment_data['DeliveryAddress1'] = $order_info['payment_address_1'];

			if ($order_info['payment_address_2']) {
				$payment_data['DeliveryAddress2'] = $order_info['payment_address_2'];
			}

			$payment_data['DeliveryCity'] = $order_info['payment_city'];
			$payment_data['DeliveryPostCode'] = $order_info['payment_postcode'];
			$payment_data['DeliveryCountry'] = $order_info['payment_iso_code_2'];

			if ($order_info['payment_iso_code_2'] == 'US') {
				$payment_data['DeliveryState'] = $order_info['payment_zone_code'];
			}

			$payment_data['DeliveryPhone'] = $order_info['telephone'];			
		}

		$payment_data['AllowGiftAid'] = '0';

		if (!$this->config->get('sagepay_transaction')) {
			$payment_data['ApplyAVSCV2'] = '0';
		}

		$payment_data['Apply3DSecure'] = '0';

		$data['transaction'] = $this->config->get('sagepay_transaction');
		$data['vendor'] = $vendor;

		$crypt_data = array();

		foreach($payment_data as $key => $value) {
			$crypt_data[] = $key . '=' . $value;
		}

		$data['crypt'] = base64_encode($this->simpleXor(utf8_decode(implode('&', $crypt_data)), $password));

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/sagepay.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/sagepay.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/sagepay.tpl', $data);
		}		
	}

	public function success() {
		if (isset($this->request->get['crypt'])) {
			$string = base64_decode(str_replace(' ', '+', $this->request->get['crypt']));
			$password = $this->config->get('sagepay_password');	

			$output = utf8_encode($this->simpleXor($string, $password));

			$data = $this->getToken($output);

			if ($data && is_array($data)) {
				$this->load->model('checkout/order');

				$this->model_checkout_order->confirm($this->request->get['order_id'], $this->config->get('config_order_status_id'));

				$message = '';

				if (isset($data['VPSTxId'])) { 
					$message .= 'VPSTxId: ' . $data['VPSTxId'] . "\n";
				}

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

				if (isset($data['CardType'])) {
					$message .= 'CardType: ' . $data['CardType'] . "\n";
				}

				if (isset($data['Last4Digits'])) {
					$message .= 'Last4Digits: ' . $data['Last4Digits'] . "\n";
				}

				if ($data['Status'] == 'OK') {
					$this->model_checkout_order->update($this->request->get['order_id'], $this->config->get('sagepay_order_status_id'), $message, false);
				} else {
					$this->model_checkout_order->update($this->request->get['order_id'], $this->config->get('config_order_status_id'), $message, false);
				}

				$this->response->redirect($this->url->link('checkout/success'));
			}
		}
	}	 

	protected function simpleXor($string, $password) {
		$data = array();

		for ($i = 0; $i < strlen($password); $i++) {
			$data[$i] = ord(substr($password, $i, 1));
		}

		$output = '';

		for ($i = 0; $i < strlen($string); $i++) {
			$output .= chr(ord(substr($string, $i, 1)) ^ ($data[$i % strlen($password)]));
		}

		return $output;		
	}

	protected function getToken($string) {
		$tokens = array(
			'Status',
			'StatusDetail',
			'VendorTxCode',
			'VPSTxId',
			'TxAuthNo',
			'Amount',
			'AVSCV2',
			'AddressResult',
			'PostCodeResult',
			'CV2Result',
			'GiftAid',
			'3DSecureStatus',
			'CAVV',
			'AddressStatus',
			'CardType',
			'Last4Digits',
			'PayerStatus',
			'CardType'
		);		

		$output = array();
		$data = array();

		for ($i = count($tokens) - 1; $i >= 0; $i--) {
			$start = strpos($string, $tokens[$i]);

			if ($start !== false) {
				$data[$i]['start'] = $start;
				$data[$i]['token'] = $tokens[$i];
			}
		}

		sort($data);

		for ($i = 0; $i < count($data); $i++) {
			$start = $data[$i]['start'] + strlen($data[$i]['token']) + 1;

			if ($i == (count($data) - 1)) {
				$output[$data[$i]['token']] = substr($string, $start);
			} else {
				$length = $data[$i+1]['start'] - $data[$i]['start'] - strlen($data[$i]['token']) - 2;

				$output[$data[$i]['token']] = substr($string, $start, $length);
			}      

		}

		return $output;
	}	
}