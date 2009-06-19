<?php
class ControllerPaymentSagePay extends Controller {
	protected function index() {
		$this->language->load('payment/sagepay');
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		
		if ($this->config->get('sagepay_test') == 'live') {
    		$this->data['action'] = 'https://live.sagepay.com/gateway/service/vspform-register.vsp';
		} elseif ($this->config->get('sagepay_test') == 'test') {
			$this->data['action'] = 'https://test.sagepay.com/gateway/service/vspform-register.vsp';		
		} elseif ($this->config->get('sagepay_test') == 'sim') {
    		$this->data['action'] = 'https://test.sagepay.com/simulator/vspformgateway.asp';
  		} 
		
		$vendor = $this->config->get('sagepay_vendor');
		$password = $this->config->get('sagepay_password');		
		
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$url  = 'VendorTxCode=' . $this->session->data['order_id'];
		$url .= '&ReferrerID=' . 'E511AF91-E4A0-42DE-80B0-09C981A3FB61';
		$url .= '&Amount=' . $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
		$url .= '&Currency=' . $order_info['currency'];
		$url .= '&Description=' . sprintf($this->language->get('text_description'), date($this->language->get('date_format_short')), $this->session->data['order_id']);
		$url .= '&SuccessURL=' . html_entity_decode($this->url->https('payment/sagepay/success&order_id=' . $this->session->data['order_id']));
		$url .= '&FailureURL=' . $this->url->https('checkout/payment');
		$url .= '&CustomerName=' . $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		$url .= '&SendEMail=1';
		$url .= '&CustomerEMail=' . $order_info['email'];
		$url .= '&VendorEMail=' . $this->config->get('config_email');  
		
		$url .= '&BillingFirstnames=' . $order_info['payment_firstname'];
        $url .= '&BillingSurname=' . $order_info['payment_lastname'];
        $url .= '&BillingAddress1=' . $order_info['payment_address_1'];
		
		if ($order_info['payment_address_2']) {
        	$url .= '&BillingAddress2=' . $order_info['payment_address_2'];
		}
		
		$url .= '&BillingCity=' . $order_info['payment_city'];
        $url .= '&BillingPostCode=' . $order_info['payment_postcode'];	
		
		$payment_address = $this->customer->getAddress($this->session->data['payment_address_id']);
		
        $url .= '&BillingCountry=' . $payment_address['iso_code_2'];
		
		if ($payment_address['iso_code_2'] == 'US') {
			$url .= '&BillingState=' . $payment_address['code'];
		}
		
		$url .= '&BillingPhone=' . $order_info['telephone'];
		
		// Check if there is a delivery address
		if (isset($this->session->data['shipping_address_id'])) {
			$url .= '&DeliveryFirstnames=' . $order_info['shipping_firstname'];
        	$url .= '&DeliverySurname=' . $order_info['shipping_lastname'];
        	$url .= '&DeliveryAddress1=' . $order_info['shipping_address_1'];
		
			if ($order_info['shipping_address_2']) {
        		$url .= '&DeliveryAddress2=' . $order_info['shipping_address_2'];
			}
		
        	$url .= '&DeliveryCity=' . $order_info['shipping_city'];
        	$url .= '&DeliveryPostCode=' . $order_info['shipping_postcode'];
		
			$shipping_address = $this->customer->getAddress($this->session->data['shipping_address_id']);
		
        	$url .= '&DeliveryCountry=' . $shipping_address['iso_code_2'];
		
			if ($shipping_address['iso_code_2'] == 'US') {
				$url .= '&DeliveryState=' . $shipping_address['code'];
			}
		
			$url .= '&DeliveryPhone=' . $order_info['telephone'];
		} else {
			$url .= '&DeliveryFirstnames=' . $order_info['payment_firstname'];
        	$url .= '&DeliverySurname=' . $order_info['payment_lastname'];
        	$url .= '&DeliveryAddress1=' . $order_info['payment_address_1'];
		
			if ($order_info['payment_address_2']) {
        		$url .= '&DeliveryAddress2=' . $order_info['payment_address_2'];
			}
		
        	$url .= '&DeliveryCity=' . $order_info['payment_city'];
        	$url .= '&DeliveryPostCode=' . $order_info['payment_postcode'];
		
			$payment_address = $this->customer->getAddress($this->session->data['payment_address_id']);
		
        	$url .= '&DeliveryCountry=' . $payment_address['iso_code_2'];
		
			if ($payment_address['iso_code_2'] == 'US') {
				$url .= '&DeliveryState=' . $payment_address['code'];
			}
		
			$url .= '&DeliveryPhone=' . $order_info['telephone'];			
		}
		
		$url .= '&AllowGiftAid=0';
		
		if (!$this->config->get('sagepay_transaction')) {
			$url .= '&ApplyAVSCV2=0';
		}
		
 		$url .= '&Apply3DSecure=0';
		
		$this->data['transaction'] = $this->config->get('sagepay_transaction');
		$this->data['vendor'] = $vendor;
		$this->data['crypt'] = base64_encode($this->simpleXor($url, $password));

		$this->data['back'] = $this->url->https('checkout/payment');
		
		$this->id       = 'payment';
		$this->template = $this->config->get('config_template') . 'payment/sagepay.tpl';
		
		$this->render();		
	}
	
	public function success() {
		if (isset($this->request->get['crypt'])) {
			$string = base64_decode(str_replace(' ', '+', $this->request->get['crypt']));
			$password = $this->config->get('sagepay_password');	

			$output = $this->simpleXor($string, $password);
			
			$data = $this->getToken($output);
		
			if ($data) {
				$this->load->model('checkout/order');
		
				$this->model_checkout_order->confirm($this->request->get['order_id'], $this->config->get('sagepay_order_status_id'));

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
				
				$this->model_checkout_order->update($this->request->get['order_id'], $this->config->get('sagepay_order_status_id'), $message, FALSE);

				$this->redirect($this->url->http('checkout/success'));
			}
		}
	}	 
	
	private function simpleXor($string, $password) {
		$data = array();

		for ($i = 0; $i < strlen(utf8_decode($password)); $i++) {
			$data[$i] = ord(substr($password, $i, 1));
		}

		$output = '';

		for ($i = 0; $i < strlen(utf8_decode($string)); $i++) {
    		$output .= chr(ord(substr($string, $i, 1)) ^ ($data[$i % strlen(utf8_decode($password))]));
		}

		return $output;		
	}
	
	private function getToken($string) {
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
  
  		for ($i = count($tokens) - 1; $i >= 0; $i--){
    		$start = strpos($string, $tokens[$i]);
    		
			if ($start){
     			$data[$i]['start'] = $start;
     			$data[$i]['token'] = $tokens[$i];
			}
		}
  
		sort($data);
		
		for ($i = 0; $i < count($data); $i++){
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
?>