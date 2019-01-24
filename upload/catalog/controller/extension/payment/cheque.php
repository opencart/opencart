<?php
class ControllerExtensionPaymentCheque extends Controller {
	public function index() {
		$this->load->language('extension/payment/cheque');

		$data['payable'] = $this->config->get('payment_cheque_payable');
		$data['address'] = nl2br($this->config->get('config_address'));

		return $this->load->view('extension/payment/cheque', $data);
	}

	public function confirm() {
		$json = array();
		
		if ($this->session->data['payment_method']['code'] == 'cheque') {
			$this->load->language('extension/payment/cheque');

			$this->load->model('checkout/order');

			$comment  = $this->language->get('text_payable') . "\n";
			$comment .= $this->config->get('payment_cheque_payable') . "\n\n";
			$comment .= $this->language->get('text_address') . "\n";
			$comment .= $this->config->get('config_address') . "\n\n";
			$comment .= $this->language->get('text_payment') . "\n";
			
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_cheque_order_status_id'), $comment, true);
			
			$json['redirect'] = $this->url->link('checkout/success', 'language=' . $this->config->get('config_language'));
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}