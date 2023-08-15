<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Payment;
/**
 * Class Cheque
 *
 * @package
 */
class Cheque extends \Opencart\System\Engine\Controller {
	/**
	 * @return string
	 */
	public function index(): string {
		$this->load->language('extension/opencart/payment/cheque');

		$data['payable'] = $this->config->get('payment_cheque_payable');
		$data['address'] = nl2br($this->config->get('config_address'));

		$data['language'] = $this->config->get('config_language');

		return $this->load->view('extension/opencart/payment/cheque', $data);
	}

	/**
	 * @return void
	 */
	public function confirm(): void {
		$this->load->language('extension/opencart/payment/cheque');

		$json = [];

		if (!isset($this->session->data['order_id'])) {
			$json['error'] = $this->language->get('error_order');
		}

		if (!isset($this->session->data['payment_method']) || $this->session->data['payment_method']['code'] != 'cheque.cheque') {
			$json['error'] = $this->language->get('error_payment_method');
		}

		if (!$json) {
			$comment  = $this->language->get('text_payable') . "\n";
			$comment .= $this->config->get('payment_cheque_payable') . "\n\n";
			$comment .= $this->language->get('text_address') . "\n";
			$comment .= $this->config->get('config_address') . "\n\n";
			$comment .= $this->language->get('text_payment') . "\n";

			$this->load->model('checkout/order');

			$this->model_checkout_order->addHistory($this->session->data['order_id'], $this->config->get('payment_cheque_order_status_id'), $comment, true);

			$json['redirect'] = $this->url->link('checkout/success', 'language=' . $this->config->get('config_language'), true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}