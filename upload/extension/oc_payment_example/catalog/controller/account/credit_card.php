<?php
namespace Opencart\Catalog\Controller\Extension\OcPaymentExample\Account;
class CreditCard extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('extension/oc_payment_example/account/credit_card');

		if (!$this->customer->isLogged() || (!isset($this->request->get['customer_token']) || !isset($this->session->data['customer_token']) || ($this->request->get['customer_token'] != $this->session->data['customer_token']))) {
			$this->session->data['redirect'] = $this->url->link('account/payment_method', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language'), true));
		}

		$data['list'] = $this->getList();

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

		$data['customer_token'] = $this->session->data['customer_token'];

		return $this->load->view('extension/oc_payment_example/account/credit_card', $data);
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('extension/oc_payment_example/account/credit_card');

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
		$data['credit_cards'] = [];

		$this->load->model('extension/oc_payment_example/payment/credit_card');

		$results = $this->model_extension_oc_payment_example_payment_credit_card->getCreditCards($this->customer->getId());

		foreach ($results as $result) {
			$data['credit_cards'][] = [
				'image'       => HTTP_SERVER . 'extension/oc_payment_example/image/' . $result['type'] . '.svg',
				'card_type'   => $this->language->get('text_' . $result['type']),
				'date_expire' => date('m-Y', strtotime($result['card_expire_year'] . '-' . $result['card_expire_month'])),
				'delete'      => $this->url->link('extension/oc_payment_example/account/credit_card.delete', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&credit_card_id=' . $result['credit_card_id'])
			] + $result;
		}

		return $this->load->view('extension/oc_payment_example/account/credit_card_list', $data);
	}

	public function save(): void {
		$this->load->language('extension/oc_payment_example/account/credit_card');

		$json = [];

		if (!$this->customer->isLogged() || (!isset($this->request->get['customer_token']) || !isset($this->session->data['customer_token']) || ($this->request->get['customer_token'] != $this->session->data['customer_token']))) {
			$this->session->data['redirect'] = $this->url->link('account/payment_method', 'language=' . $this->config->get('config_language'));

			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		$keys = [
			'card_name',
			'type',
			'card_number',
			'card_expire_month',
			'card_expire_year',
			'card_cvv'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		if (!$this->config->get('payment_credit_card_status')) {
			$json['error']['warning'] = $this->language->get('error_credit_card');
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
			$this->load->model('extension/oc_payment_example/payment/credit_card');

			$this->model_extension_oc_payment_example_payment_credit_card->addCreditCard($this->customer->getId(), ['card_number' => '**** **** **** ' . substr($this->request->post['card_number'], -4)] + $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Delete Credit Card
	 */
	public function delete(): void {
		$this->load->language('extension/oc_payment_example/account/credit_card');

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
