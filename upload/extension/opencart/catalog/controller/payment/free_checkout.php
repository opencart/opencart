<?php
namespace Opencart\Application\Controller\Extension\Opencart\Payment;
class FreeCheckout extends \Opencart\System\Engine\Controller {
	public function index() {
		return $this->load->view('extension/payment/free_checkout');
	}

	public function confirm() {
		$json = [];

		if ($this->session->data['payment_method']['code'] == 'free_checkout') {
			$this->load->model('checkout/order');

			$this->model_checkout_order->addHistory($this->session->data['order_id'], $this->config->get('payment_free_checkout_order_status_id'));

			$json['redirect'] = str_replace( '&amp;', '&', $this->url->link('checkout/success', 'language=' . $this->config->get('config_language')));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
