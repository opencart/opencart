<?php
class ControllerExtensionPaymentAlipayCross extends Controller {
	var $alipay_gateway = 'https://mapi.alipay.com/gateway.do?';
	var $alipay_gateway_test = 'https://openapi.alipaydev.com/gateway.do?';

	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$out_trade_no = str_pad($order_info['order_id'], 7, "0",STR_PAD_LEFT); // Length must be greater than 7
		$subject = trim($this->config->get('config_name'));
		$currency = $this->config->get('payment_alipay_cross_currency');
		$total_fee = trim($this->currency->format($order_info['total'], $currency, '', false));
		$total_fee_cny = trim($this->currency->format($order_info['total'], 'CNY', '', false));
		$body = trim($this->config->get('config_name'));

		$alipay_config = array (
			'partner'              => $this->config->get('payment_alipay_cross_app_id'),
			'key'                  => $this->config->get('payment_alipay_cross_merchant_private_key'),
			'notify_url'           => HTTPS_SERVER . "payment_callback/alipay_cross",
			'return_url'           => $this->url->link('checkout/success'),
			'sign_type'            => strtoupper('MD5'),
			'input_charset'        => strtolower('utf-8'),
			'cacert'               => getcwd().'/cacert.pem',
			'transport'            => 'https',
			'service'              => 'create_forex_trade'
		);

		$parameter = array(
			"service"        => $alipay_config['service'],
			"partner"        => $alipay_config['partner'],
			"notify_url"     => $alipay_config['notify_url'],
			"return_url"     => $alipay_config['return_url'],

			"out_trade_no"   => $out_trade_no,
			"subject"        => $subject,
			"body"           => $body,
			"currency"       => $currency,
			"_input_charset" => trim(strtolower($alipay_config['input_charset']))
		);
		if ($this->session->data['currency'] == 'CNY') {
			$parameter['rmb_fee'] = $total_fee_cny;
		} else {
			$parameter['total_fee'] = $total_fee;
		}

		$this->load->model('extension/payment/alipay_cross');
		$data['params'] = $this->model_extension_payment_alipay_cross->buildRequestPara($alipay_config, $parameter);
		$gateway = $this->config->get('payment_alipay_cross_test') == "sandbox" ? $this->alipay_gateway_test : $this->alipay_gateway;
		$data['action'] = $gateway . "_input_charset=".trim($alipay_config['input_charset']);

		return $this->load->view('extension/payment/alipay_cross', $data);
	}

	public function callback() {
		$this->log->write('alipay cross payment notify:');
		$alipay_config = array (
			'partner'              => $this->config->get('payment_alipay_cross_app_id'),
			'key'                  => $this->config->get('payment_alipay_cross_merchant_private_key'),
			'sign_type'            => strtoupper('MD5'),
			'input_charset'        => strtolower('utf-8'),
			'cacert'               => getcwd().'/cacert.pem'
		);
		$this->load->model('extension/payment/alipay_cross');
		$this->log->write('config: ' . var_export($alipay_config,true));
		$verify_result = $this->model_extension_payment_alipay_cross->verifyNotify($alipay_config);

		if($verify_result) {//check successed
			$this->log->write('Alipay cross check successed');
			$order_id = $_POST['out_trade_no'];
			if($_POST['trade_status'] == 'TRADE_FINISHED') {
				$this->load->model('checkout/order');
				$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_alipay_cross_order_status_id'));
			} else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
			}
			echo "success"; //Do not modified or deleted
		} else {
			$this->log->write('Alipay cross check failed');
			//chedk failed
			echo "fail";

		}
	}
}
