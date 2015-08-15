<?php
class ControllerCaptchaBasicCaptcha extends Controller {
	public function index() {
		$this->load->language('captcha/basic_captcha');

        $data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/payza.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/payza.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/payza.tpl', $data);
		}
	}

	public function callback() {

	}
}
