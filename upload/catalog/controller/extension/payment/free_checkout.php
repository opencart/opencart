<?php
class ControllerExtensionPaymentFreeCheckout extends Controller {
	public function index() {
		$data['continue'] = $this->url->link('checkout/success', 'language=' . $this->config->get('config_language'));

		return $this->load->view('extension/payment/free_checkout', $data);
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'free_checkout') {
			$this->load->model('checkout/order');

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_free_checkout_order_status_id'));
		}
	}
}
