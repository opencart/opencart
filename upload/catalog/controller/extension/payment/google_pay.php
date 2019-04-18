<?php
class ControllerExtensionPaymentGooglePay extends Controller {
	private $merchant_id = 12345;

	public function index() {
		$this->load->language('extension/payment/google_pay');

		$this->load->model('checkout/order');
		$this->load->model('extension/payment/google_pay');

		$data = array();

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$data['total_price'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$data['currency_code'] = $order_info['currency_code'];

		$data['api_version_major'] = 2;
		$data['api_version_minor'] = 0;
		$data['merchant_id'] = $this->merchant_id;
		$data['merchant_name'] = $this->config->get("payment_google_pay_merchant_name");
		$data['button_color'] = $this->config->get("payment_google_pay_button_color");
		$data['button_type'] = $this->config->get("payment_google_pay_button_type");
		$data['debug_log'] = $this->config->get("payment_google_pay_debug");
		$data['accept_prepay_cards'] = $this->config->get("payment_google_pay_accept_prepay_cards");

		$data['allowed_card_networks'] = $this->config->get("payment_google_pay_allow_card_networks");
		$data['allowed_auth'] = $this->config->get("payment_google_pay_allow_auth_methods");
		$data['environment'] = $this->config->get("payment_google_pay_environment");

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