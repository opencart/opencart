<?php
namespace Opencart\Catalog\Controller\Extension\OcPaymentExample\Payment;
class CreditCard extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('extension/oc_payment_example/payment/credit_card');

		if (isset($this->session->data['payment_method'])) {
			$data['logged'] = $this->customer->isLogged();
			$data['subscription'] = $this->cart->hasSubscription();

			$data['types'] = [];

			foreach (['visa', 'mastercard'] as $type) {
				$data['types'][] = [
					'text'  => $this->language->get('text_' . $type),
					'value' => $type
				];
			}

			$data['months'] = [];

			foreach (range(1, 12) as $month) {
				$data['months'][] = date('m', mktime(0, 0, 0, $month, 1));
			}

			$data['years'] = [];

			foreach (range(date('Y'), date('Y', strtotime('+10 year'))) as $year) {
				$data['years'][] = $year;
			}

			$data['language'] = $this->config->get('config_language');

			// Card storage
			if ($this->session->data['payment_method']['code'] == 'credit_card.credit_card') {
				return $this->load->view('extension/oc_payment_example/payment/credit_card', $data);
			} else {
				$data['text_title'] = $this->session->data['payment_method']['name'];

				return $this->load->view('extension/oc_payment_example/payment/stored', $data);
			}
		}

		return '';
	}

	public function confirm(): void {
		$this->load->language('extension/oc_payment_example/payment/credit_card');

		$json = [];

		if (isset($this->session->data['order_id'])) {
			$order_id = $this->session->data['order_id'];
		} else {
			$order_id = 0;
		}

		$keys = [
			'card_name',
			'type',
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

		if ($order_id) {
			$this->load->model('checkout/order');

			$order_info = $this->model_checkout_order->getOrder($order_id);

			if (!$order_info) {
				$json['redirect'] = $this->url->link('checkout/failure', 'language=' . $this->config->get('config_language'), true);

				unset($this->session->data['order_id']);
			}
		} else {
			$json['error']['warning'] = $this->language->get('error_order');
		}

		if (!$this->config->get('payment_credit_card_status') || !isset($this->session->data['payment_method']) || $this->session->data['payment_method']['code'] != 'credit_card.credit_card') {
			$json['error']['warning'] = $this->language->get('error_payment_method');
		}

		if (!$this->request->post['card_name']) {
			$json['error']['card_name'] = $this->language->get('error_card_name');
		}

		if (!in_array($this->request->post['type'], ['visa', 'mastercard'])) {
			$json['error']['card_type'] = $this->language->get('error_card_type');
		}

		if (!preg_match('/[0-9\s]{8,19}/', $this->request->post['card_number'])) {
			$json['error']['card_number'] = $this->language->get('error_card_number');
		}

		if (strtotime((int)$this->request->post['card_expire_year'] . '-' . $this->request->post['card_expire_month'] . '-01') < time()) {
			$json['error']['card_expire'] = $this->language->get('error_card_expired');
		}

		if (strlen($this->request->post['card_cvv']) != 3) {
			$json['error']['card_cvv'] = $this->language->get('error_card_cvv');
		}

		if (!$json) {
			// Card storage
			if ($this->customer->isLogged() && ($this->request->post['store'] || $this->cart->hasSubscription())) {
				$credit_card_data = [
					'card_number' => '**** **** **** ' . substr($this->request->post['card_number'], -4),
					'date_expire' => $this->request->post['card_expire_year'] . '-' . $this->request->post['card_expire_month'] . '-01'
				] + $this->request->post;

				$this->load->model('extension/oc_payment_example/payment/credit_card');

				$credit_card_id = $this->model_extension_oc_payment_example_payment_credit_card->addCreditCard($this->customer->getId(), $credit_card_data);
			} else {
				$credit_card_id = 0;
			}

			// Credit Card charge code goes here
			$response = $this->config->get('payment_credit_card_response');

			// Add report to the credit card table
			$report_data = [
				'order_id'       => $order_id,
				'credit_card_id' => $credit_card_id,
				'card_number'    => $this->request->post['card_number'],
				'type'           => $this->request->post['type'],
				'amount'         => $order_info['total'],
				'response'       => $response
			];

			$this->model_extension_oc_payment_example_payment_credit_card->addReport($this->customer->getId(), $report_data);

			if ($response) {
				$this->model_checkout_order->addHistory($order_id, $this->config->get('payment_credit_card_approved_status_id'), '', true);

				$json['redirect'] = $this->url->link('checkout/success', 'language=' . $this->config->get('config_language'), true);
			} else {
				$this->model_checkout_order->addHistory($order_id, $this->config->get('payment_credit_card_denied_status_id'), '', true);

				$json['redirect'] = $this->url->link('checkout/failure', 'language=' . $this->config->get('config_language'), true);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Stored
	 *
	 * Called when customer has selected to use stored payment method
	 *
	 * @return string
	 */
	public function stored(): void {
		$this->load->language('extension/oc_payment_example/payment/credit_card');

		$json = [];

		if (isset($this->session->data['order_id'])) {
			$order_id = $this->session->data['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if (!$order_info) {
			$json['error']['warning'] = $this->language->get('error_order');
		}

		if (!$this->customer->isLogged()) {
			$json['error']['warning'] = $this->language->get('error_login');
		}

		if (!$this->config->get('payment_credit_card_status') || strstr($order_info['payment_method']['code'], '.', true) != 'credit_card') {
			$json['error']['warning'] = $this->language->get('error_payment_method');
		}

		$this->load->model('extension/oc_payment_example/payment/credit_card');

		$credit_card_id = substr(strstr($order_info['payment_method']['code'], '.'), 1);

		$credit_card_info = $this->model_extension_oc_payment_example_payment_credit_card->getCreditCard($this->customer->getId(), $credit_card_id);

		if (!$credit_card_info) {
			$json['error']['warning'] = $this->language->get('error_credit_card');
		}

		if (!$json) {
			$response = $this->config->get('payment_credit_card_response');

			// Add report to the credit card table
			$report_data = [
				'order_id'       => $order_id,
				'credit_card_id' => $credit_card_info['credit_card_id'],
				'card_number'    => $credit_card_info['card_number'],
				'type'           => $credit_card_info['type'],
				'amount'         => $order_info['total'],
				'response'       => $response
			];

			$this->model_extension_oc_payment_example_payment_credit_card->addReport($this->customer->getId(), $report_data);

			if ($response) {
				// Credit Card charge code goes here
				$this->model_checkout_order->addHistory($order_id, $this->config->get('payment_credit_card_approved_status_id'), '', true);

				$json['redirect'] = $this->url->link('checkout/success', 'language=' . $this->config->get('config_language'), true);
			} else {
				$this->model_checkout_order->addHistory($order_id, $this->config->get('payment_credit_card_denied_status_id'), '', true);

				$json['redirect'] = $this->url->link('checkout/failure', 'language=' . $this->config->get('config_language'), true);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
