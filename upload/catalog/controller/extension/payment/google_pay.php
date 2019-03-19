<?php
class ControllerExtensionPaymentGooglePay extends Controller {
	public function index() {
		$this->load->language('extension/payment/google_pay');

		$this->load->model('checkout/order');
		$this->load->model('extension/payment/google_pay');

		$this->load->library('googlepay');

		$data = array();

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$data['total_price'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$data['currency_code'] = $order_info['currency_code'];

		$data['api_version_major'] = GooglePay::API_VERSION_MAJOR;
		$data['api_version_minor'] = GooglePay::API_VERSION_MINOR;
		$data['merchant_id'] = GooglePay::MERCHANT_ID;
		$data['merchant_name'] = $this->config->get("payment_google_pay_merchant_name");
		$data['button_color'] = $this->config->get("payment_google_pay_button_color");
		$data['button_type'] = $this->config->get("payment_google_pay_button_type");
		$data['debug_log'] = $this->config->get("payment_google_pay_debug");


		$data['allowed_card_networks'] = $this->config->get("payment_google_pay_allow_card_networks");
		$data['allowed_auth'] = $this->config->get("payment_google_pay_allow_auth_methods");
		$data['environment'] = $this->config->get("payment_google_pay_environment");

//			$data['tokenization_params'] = array(
//				'gateway' => 'example',
//				'gatewayMerchantId' => 'exampleGatewayMerchantId',
//			);

		$gateway = $this->config->get("payment_google_pay_merchant_gateway");
		$gateway_params = $this->config->get("payment_google_pay_merchant_param");

		$tokenization_params = array('gateway' => $gateway);

		$data['tokenization_params'] = array_merge($tokenization_params, $gateway_params[$gateway]);


		return $this->load->view('extension/payment/google_pay', $data);
	}

	public function js($route, &$data) {
		$this->document->addScript('https://pay.google.com/gp/p/js/pay.js');
	}
}