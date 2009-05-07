<?php
class ControllerPaymentSagePay extends Controller {
	protected function index() {
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
		


		$this->data['back'] = $this->url->https('checkout/payment');
		
		$this->id       = 'payment';
		$this->template = $this->config->get('config_template') . 'payment/sagepay.tpl';
		
		$this->render();		
	}
	
	public function send() {
		if ($this->config->get('sagepay_test') == 'sim') {
    		$this->data['action'] = 'https://test.sagepay.com/Simulator/VSPFormGateway.asp';

			$vendor   = $this->config->get('sagepay_vendor');
			$password = $this->config->get('sagepay_password');	
  		} elseif ($this->config->get('sagepay_test') == 'test') {
			$this->data['action'] = 'https://test.sagepay.com/gateway/service/vpsform-register.vsp';
			
			$vendor   = 'testvendor';
			$password = 'testvendor';			
		} elseif ($this->config->get('sagepay_test') == 'live') {
    		$this->data['action'] = 'https://live.sagepay.com/gateway/service/vpsform-register.vsp';

			$vendor   = $this->config->get('sagepay_vendor');
			$password = $this->config->get('sagepay_password');
		}		
		
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$query  = 'VendorTxCode=' . date("dmYHis") . $this->session->data['order_id'];
		$query .= '&ReferrerID=' . '{E511AF91-E4A0-42DE-80B0-09C981A3FB61}';
		$query .= '&Amount=' . $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
		$query .= '&Currency=' . $order_info['currency'];
		$query .= '&Description=' . sprintf($this->language->get('description'), date($this->language->get('date_format_short')), $this->session->data['order_id']);
		$query .= '&NotificationURL=' . $this->url->https('payment/sagepay/callback');
		$query .= '&SuccessURL=' . $this->url->https('checkout/success');
		$query .= '&FailureURL=' . $this->url->https('checkout/payment');
		$query .= '&CustomerName=' . $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		$query .= '&CustomerEMail=' . $order_info['email'];
		$query .= '&VendorEMail=' . $this->config->get('config_email');
        $query .= '&SendEMail=' . '1';
        $query .= '&EMailMessage=' . $order_info['comment'];		
        $query .= '&BillingSurname=' . $order_info['payment_lastname'];
        $query .= '&BillingFirstnames=' . $order_info['payment_firstname'];
        $query .= '&BillingAddress1=' . $order_info['payment_address_1'];
        $query .= '&BillingAddress2=' . $order_info['payment_address_2'];
        $query .= '&BillingCity=' . $order_info['payment_city'];
        $query .= '&BillingPostCode=' . $order_info['payment_postcode'];
		
		$this->load->model('localisation/country');
		
		$country_info = $this->model_localisation_country->getCountry($this->session->data['payment_address_id']);
		
        $query .= '&BillingCountry=' . $country_info['iso_code_2'];
		$query .= '&BillingPhone=' . $order_info['telephone'];

        $query .= '&DeliverySurname=' . $order_info['shipping_lastname'];
        $query .= '&DeliveryFirstnames=' . $order_info['shipping_firstname'];
        $query .= '&DeliveryAddress1=' . $order_info['shipping_address_1'];
        $query .= '&DeliveryAddress2=' . $order_info['shipping_address_2'];
        $query .= '&DeliveryCity=' . $order_info['shipping_city'];
        $query .= '&DeliveryPostCode=' . $order_info['shipping_postcode'];
		
		$country_info = $this->model_localisation_country->getCountry($this->session->data['shipping_address_id']);
		
        $query .= '&DeliveryCountry=' . $country_info['iso_code_2'];
        $query .= '&DeliveryPhone=' . $order_info['telephone'];	

		$data = array();

		for ($i = 0; $i < strlen(utf8_decode($password)); $i++) {
			$data[$i] = ord(substr($password, $i, 1));
		}

		$output = '';

		for ($i = 0; $i < strlen(utf8_decode($query)); $i++) {
    		$output .= chr(ord(substr($query, $i, 1)) ^ ($data[$i % strlen(utf8_decode($password))]));
		}

		$crypt = base64_encode($output);	
	
		$this->data['vendor'] = $vendor;
		$this->data['crypt'] = $crypt;
		
		/*
		$this->load->model('checkout/order');
		
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
		*/
		
	}	 
}
?>