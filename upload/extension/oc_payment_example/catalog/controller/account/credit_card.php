<?php
namespace Opencart\Catalog\Controller\Extension\OcPaymentExample\Account;
class CreditCard extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('account/payment_method');

		if (!$this->customer->isLogged() || (!isset($this->request->get['customer_token']) || !isset($this->session->data['customer_token']) || ($this->request->get['customer_token'] != $this->session->data['customer_token']))) {
			$this->session->data['redirect'] = $this->url->link('account/payment_method', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language'), true));
		}

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	protected function getList(): string {
		$data['payment_methods'] = [];

		$this->load->model('setting/extension');

		$results = $this->model_setting_extension->getExtensionsByType('payment');

		foreach ($results as $result) {
			if ($this->config->get('payment_' . $result['code'] . '_status')) {
				$output = $this->load->controller('extension/' . $result['extension'] . '/account/' . $result['code']);

				if (!$output instanceof \Exception) {
					$data['payment_methods'][] = $output;
				}
			}
		}

		return $this->load->view('extension/oc_payment_example/account/credit_card_list', $data);
	}

	public function add(): string {
		$this->load->language('extension/oc_payment_example/payment/credit_card');

		if (isset($this->session->data['payment_method'])) {
			$data['logged'] = $this->customer->isLogged();
			$data['subscription'] = $this->cart->hasSubscription();

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

	public function save(): void {
		$this->load->language('extension/oc_payment_example/payment/credit_card');

		$json = [];

		if (isset($this->session->data['order_id'])) {
			$order_id = $this->session->data['order_id'];
		} else {
			$order_id = 0;
		}

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

		if (isset($this->session->data['order_id'])) {
			$this->load->model('checkout/order');

			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

			if (!$order_info) {
				$json['redirect'] = $this->url->link('checkout/failure', 'language=' . $this->config->get('config_language'), true);

				unset($this->session->data['order_id']);
			}
		} else {
			$json['error'] = $this->language->get('error_order');
		}

		if (!$this->config->get('payment_credit_card_status') || !isset($this->session->data['payment_method']) || $this->session->data['payment_method']['code'] != 'credit_card.credit_card') {
			$json['error']['warning'] = $this->language->get('error_payment_method');
		}

		if (!$this->request->post['card_name']) {
			$json['error']['card_name'] = $this->language->get('error_card_name');
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
			/*
			*
			* Credit Card charge code goes here
			*
			*/

			$response = $this->config->get('payment_credit_card_response');

			// Card storage
			if ($this->customer->isLogged() && ($this->request->post['store'] || $this->cart->hasSubscription())) {
				$credit_card_data = [
					'card_name'         => $this->request->post['card_name'],
					'card_number'       => '**** **** **** ' . substr($this->request->post['card_number'], -4),
					'card_expire_month' => $this->request->post['card_expire_month'],
					'card_expire_year'  => $this->request->post['card_expire_year'],
					'card_cvv'          => $this->request->post['card_cvv'],
					'date_expire'       => $this->request->post['card_expire_year'] . '-' . $this->request->post['card_expire_month'] . '-01'
				];

				$this->load->model('extension/oc_payment_example/payment/credit_card');

				$this->model_extension_oc_payment_example_payment_credit_card->addCreditCard($this->customer->getId(), $credit_card_data);
			}

			// Set Credit Card response
			if ($response) {
				$this->load->model('checkout/order');

				$this->model_checkout_order->addHistory($this->session->data['order_id'], $this->config->get('payment_credit_card_approved_status_id'), '', true);

				$json['redirect'] = $this->url->link('checkout/success', 'language=' . $this->config->get('config_language'), true);
			} else {
				$this->load->model('checkout/order');

				$this->model_checkout_order->addHistory($this->session->data['order_id'], $this->config->get('payment_credit_card_failed_status_id'), '', true);

				$json['redirect'] = $this->url->link('checkout/failure', 'language=' . $this->config->get('config_language'), true);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('extension/oc_payment_example/payment/credit_card');

		$json = [];

		if (isset($this->request->get['credit_card_id'])) {
			$credit_card_id = (int)$this->request->get['credit_card_id'];
		} else {
			$credit_card_id = 0;
		}

		if (!$this->customer->isLogged()) {
			$json['error'] = $this->language->get('error_logged');
		}

		$this->load->model('extension/oc_payment_example/payment/credit_card');

		$credit_card_info = $this->model_extension_oc_payment_example_payment_credit_card->getCreditCard($this->customer->getId(), $credit_card_id);

		if (!$credit_card_info) {
			$json['error'] = $this->language->get('error_credit_card');
		}

		if (!$json) {
			$this->model_extension_oc_payment_example_payment_credit_card->deleteCreditCard($this->customer->getId(), $credit_card_id);

			$json['success'] = $this->language->get('text_delete');

			// Clear payment and shipping methods
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
