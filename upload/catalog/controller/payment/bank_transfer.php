<?php
class ControllerPaymentBankTransfer extends Controller {
	public function index() {
		$this->load->language('payment/bank_transfer');

		$data['text_instruction'] = $this->language->get('text_instruction');
		$data['text_description'] = $this->language->get('text_description');
		$data['text_payment'] = $this->language->get('text_payment');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['bank'] = nl2br($this->config->get('bank_transfer_bank' . $this->config->get('config_language_id')));

		$data['continue'] = $this->url->link('checkout/success');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/bank_transfer.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/bank_transfer.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/bank_transfer.tpl', $data);
		}
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'bank_transfer') {
			$this->load->language('payment/bank_transfer');

			$this->load->model('checkout/order');

			$comment  = $this->language->get('text_instruction') . "\n\n";
			$comment .= $this->config->get('bank_transfer_bank' . $this->config->get('config_language_id')) . "\n\n";
			$comment .= $this->language->get('text_payment');

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('bank_transfer_order_status_id'), $comment, true);
		}
	}
}