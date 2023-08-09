<?php
namespace Opencart\Catalog\Controller\Account;
/**
 * Class Payment Method
 *
 * @package Opencart\Catalog\Controller\Account
 */
class PaymentMethod extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
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

		$data['list'] = $this->getList();

		$data['continue'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);

		$data['language'] = $this->config->get('config_language');

		$data['customer_token'] = $this->session->data['customer_token'];

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/payment_method', $data));
	}

	/**
	 * @return void
	 */
	public function list(): void {
		$this->load->language('account/payment_method');

		if (!$this->customer->isLogged() || (!isset($this->request->get['customer_token']) || !isset($this->session->data['customer_token']) || ($this->request->get['customer_token'] != $this->session->data['customer_token']))) {
			$this->session->data['redirect'] = $this->url->link('account/payment_method', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
		}

		$this->response->setOutput($this->getList());
	}

	/**
	 * @return string
	 */
	protected function getList(): string {
		$data['payment_methods'] = [];

		$this->load->model('account/payment_method');

		$this->load->model('setting/extension');

		$results = $this->model_setting_extension->getExtensionsByType('payment');

		foreach ($results as $result) {
			if ($this->config->get('payment_' . $result['code'] . '_status')) {
				$this->load->model('extension/' . $result['extension'] . '/payment/' . $result['code']);

				//$payment_method = $this->{'model_extension_' . $result['extension'] . '_payment_' . $result['code']}->getMethods($payment_address);

				if ($payment_method) {
					$method_data[$result['code']] = $payment_method;
				}
			}
		}


		foreach ($results as $result) {
			$data['payment_methods'][] = [
				'code'        => $result['code'],
				'name'        => $result['name'],
				'image'       => $result['image'],
				'type'        => $result['type'],
				'date_expire' => date('m-Y', strtotime($result['date_expire'])),
				'delete'      => $this->url->link('account/payment_method.delete', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&customer_payment_id=' . $result['customer_payment_id'])
			];
		}

		return $this->load->view('account/payment_method_list', $data);
	}

	/**
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('account/payment_method');

		$json = [];

		if (isset($this->request->get['customer_payment_id'])) {
			$customer_payment_id = (int)$this->request->get['customer_payment_id'];
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

			// Delete payment method from database.
			$this->model_account_payment_method->deletePaymentMethod($customer_payment_id);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
