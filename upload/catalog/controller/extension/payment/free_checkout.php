<?php
class ControllerPaymentFreeCheckout extends Controller {
	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['text_loading'] = $this->language->get('text_loading');

		$data['continue'] = $this->url->link('checkout/success');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/free_checkout.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/free_checkout.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/free_checkout.tpl', $data);
		}
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'free_checkout') {
			$this->load->model('checkout/order');

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('free_checkout_order_status_id'));
		}
	}
}