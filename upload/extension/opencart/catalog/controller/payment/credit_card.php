<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Payment;
class CreditCard extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('extension/opencart/payment/credit_card');

		$data['months'] = range(1, 12);

		$data['years'] = [];

		foreach (range(date('Y'), date('Y', strtotime('+10 year'))) as $year) {
			$data['years'][] = $year;
		}

		$data['language'] = $this->config->get('config_language');

		return $this->load->view('extension/opencart/payment/credit_card', $data);
	}

	public function confirm(): void {
		$this->load->language('extension/opencart/payment/credit_card');

		$json = [];

		$keys = [
			'card_name',
			'card_number',
			'card_expire_month',
			'card_expire_year',
			'card_cvv',
			'store'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		if (!isset($this->session->data['order_id'])) {
			$json['error']['warning'] = $this->language->get('error_order');
		}

		if (!isset($this->session->data['payment_method']) || $this->session->data['payment_method'] != 'credit_card') {
			$json['error'] ['warning'] = $this->language->get('error_payment_method');
		}

		if (!$this->request->post['card_name']) {
			$json['error']['card_name'] = $this->language->get('error_card_name');
		}

		if (preg_match($this->request->post['card_number'], '[^0-9\s]{13,19}')) {
			$json['error']['card_number'] = $this->language->get('error_card_number');
		}

		if (!in_array($this->request->post['card_expire_month'], range(1, 12)) || !in_array($this->request->post['card_expire_year'], range(date('Y'), date('Y', strtotime('+10 year'))))) {
			$json['error']['card_expire'] = $this->language->get('error_card_expire');
		}

		if (strlen($this->request->post['card_cvv']) != 3) {
			$json['error']['card_cvv'] = $this->language->get('error_card_cvv');
		}

		if (!$json) {
			// Card storage

			if ($this->request->post['store']) {
				$this->load->model('account/payment_method');

				//$this->model_account_payment_method->addPaymentMethod($this->session->data['order_id'], $this->config->get('payment_bank_transfer_order_status_id'), '', true);
			}


			$this->load->model('checkout/order');

			$this->model_checkout_order->addHistory($this->session->data['order_id'], $this->config->get('payment_credit_card_order_status_id'), '', true);

			$json['redirect'] = $this->url->link('checkout/success', 'language=' . $this->config->get('config_language'), true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
