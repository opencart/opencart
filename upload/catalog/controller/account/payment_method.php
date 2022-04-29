<?php
namespace Opencart\Catalog\Controller\Account;
class PaymentMethod extends \Opencart\System\Engine\Controller {
	 public function index(): void {
		$this->load->language('account/payment_method');

		if (!$this->customer->isLogged() || (!isset($this->request->get['customer_token']) || !isset($this->session->data['customer_token']) || ($this->request->get['customer_token'] != $this->session->data['customer_token']))) {
			$this->session->data['redirect'] = $this->url->link('account/payment_method', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/payment_method', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
		];

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['back'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);

		$data['list'] = $this->getList();

		$data['customer_token'] = $this->session->data['customer_token'];

		$data['language'] = $this->config->get('config_language');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/payment_method', $data));
	}

	public function list(): void {
		$this->load->language('account/payment_method');

		if (!$this->customer->isLogged() || (!isset($this->request->get['customer_token']) || !isset($this->session->data['customer_token']) || ($this->request->get['customer_token'] != $this->session->data['customer_token']))) {
			$this->session->data['redirect'] = $this->url->link('account/payment_method', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
		}

		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
		$data['payment_methods'] = [];

		$this->load->model('account/payment_method');

		$results = $this->model_account_payment_method->getPaymentMethods($this->customer->getId());

		foreach ($results as $result) {
			$data['payment_methods'][] = [
				'customer_payment_id' => $result['customer_payment_id'],
				'name'                => $result['name'],
				'image'               => $result['image'],
				'type'                => $result['type'],
				'date_expire'         => date('m-Y', strtotime($result['date_expire'])),
				'delete'              => $this->url->link('account/payment_method|delete', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&customer_payment_id=' . $result['customer_payment_id'])
			];
		}

		return $this->load->view('account/payment_method_list', $data);
	}

	public function delete(): void {
		$this->load->language('account/payment_method');

		$json = [];

		if (isset($this->request->get['customer_payment_id'])) {
			$customer_payment_id = $this->request->get['customer_payment_id'];
		} else {
			$customer_payment_id = 0;
		}

		if (!$this->customer->isLogged() || (!isset($this->request->get['customer_token']) || !isset($this->session->data['customer_token']) || ($this->request->get['customer_token'] != $this->session->data['customer_token']))) {
			$this->session->data['redirect'] = $this->url->link('account/payment_method', 'language=' . $this->config->get('config_language'));

			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			$this->load->model('account/payment_method');

			$payment_method_info = $this->model_account_payment_method->getPaymentMethod($this->customer->getId(), $customer_payment_id);

			if (!$payment_method_info) {
				$json['error'] = $this->language->get('error_payment_method');
			}
		}

		if (!$json) {
			$this->load->model('extension/' . $payment_method_info['extension'] . '/payment/' . $payment_method_info['code']);

			if ($this->{'model_extension_' . $payment_method_info['extension'] . '_payment_' . $payment_method_info['code']}->delete($customer_payment_id)) {

			}

			// Delete address from database.
			$this->model_account_payment_method->deletePaymentMethod($customer_payment_id);

			// Delete address from session.
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}