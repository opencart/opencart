<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Payment;
class CreditCard extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('extension/opencart/payment/credit_card');

		$data['logged'] = $this->customer->isLogged();

		$data['months'] = [];

		foreach (range(1, 12) as $month) {
			$data['months'][] = date('m', mktime(0, 0, 0, $month));
		}

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

		if (!preg_match('/[0-9\s]{8,19}/', $this->request->post['card_number'])) {
			$json['error']['card_number'] = $this->language->get('error_card_number');
		}

		if ($this->request->post['card_expire_year'] && $this->request->post['card_expire_month']) {
			if (strtotime((int)$this->request->post['card_expire_year'] . '-' . $this->request->post['card_expire_month'] . '-01') < time()) {
				$json['error']['card_expire'] = $this->language->get('error_card_expired');
			}
		} else {
			$json['error']['card_expire'] = $this->language->get('error_card_expire');
		}

		if (strlen($this->request->post['card_cvv']) != 3) {
			$json['error']['card_cvv'] = $this->language->get('error_card_cvv');
		}

		if (!$json) {
			// Card storage
			if ($this->customer->isLogged() && $this->request->post['store']) {
				$this->load->model('account/payment_method');

				$payment_method_data = [
					'name'        => '**** **** **** ' . substr($this->request->post['card_number'], -4),
					'image'       => 'visa.png',
					'type'        => 'visa',
					'extension'   => 'opencart',
					'code'        => 'credit_card',
					'token'       => md5(rand()),
					'date_expire' => $this->request->post['card_expire_year'] . '-' . $this->request->post['card_expire_month'] . '-01',
					'default'     => !$this->model_account_payment_method->getTotalPaymentMethods() ? true : false
				];

				$this->model_account_payment_method->addPaymentMethod($payment_method_data);
			}

			$this->load->model('checkout/order');

			$this->model_checkout_order->addHistory($this->session->data['order_id'], $this->config->get('payment_credit_card_order_status_id'), '', true);

			$json['redirect'] = $this->url->link('checkout/success', 'language=' . $this->config->get('config_language'), true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function callback(): void {

	}
}
