<?php
class ControllerPaymentBankTransfer extends Controller {
	protected function index() {
		$this->language->load('payment/bank_transfer');
		
		$this->data['text_instruction'] = $this->language->get('text_instruction');
		$this->data['text_payment'] = $this->language->get('text_payment');
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		
		$this->data['bank'] = nl2br($this->config->get('bank_transfer_bank_' . $this->language->getId()));

		$this->data['continue'] = $this->url->https('checkout/success');
		$this->data['back'] = $this->url->https('checkout/payment');
		
		$this->id       = 'payment';
		$this->template = $this->config->get('config_template') . 'payment/bank_transfer.tpl';
		
		$this->render(); 
	}
	
	public function confirm() {
		$this->language->load('payment/bank_transfer');
		
		$this->load->model('checkout/order');
		
		$comment  = $this->language->get('text_instruction') . "\n\n";
		$comment .= $this->config->get('bank_transfer_bank_' . $this->language->getId()) . "\n\n";
		$comment .= $this->language->get('text_payment');
		
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('bank_transfer_order_status_id'), $comment);
	}
}
?>