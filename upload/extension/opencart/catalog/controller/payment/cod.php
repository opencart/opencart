<?php
namespace Opencart\Application\Controller\Extension\Opencart\Payment;
class Cod extends \Opencart\System\Engine\Controller {
	public function index() {
		return $this->load->view('extension/opencart/payment/cod');
	}

	public function confirm() {
		$json = [];

		if ($this->session->data['payment_method']['code'] == 'cod') {
			$this->load->model('checkout/order');

			$this->model_checkout_order->addHistory($this->session->data['order_id'], $this->config->get('payment_cod_order_status_id'));

			$json['redirect'] = $this->url->link('checkout/success', 'language=' . $this->config->get('config_language'), true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
