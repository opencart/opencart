<?php
namespace Opencart\Catalog\Controller\Api\Sale;
use \Opencart\System\Helper as Helper;
class Voucher extends \Opencart\System\Engine\Controller {
	// Apply voucher
	public function index(): void {
		$this->load->language('api/sale/voucher');

		$json = [];

		if (isset($this->request->post['voucher'])) {
			$voucher = (string)$this->request->post['voucher'];
		} else {
			$voucher = '';
		}

		if ($voucher) {
			$this->load->model('checkout/voucher');

			$voucher_info = $this->model_checkout_voucher->getVoucher($voucher);

			if (!$voucher_info) {
				$json['error'] = $this->language->get('error_voucher');
			}
		}

		if (!$json) {
			if ($voucher) {
				$this->session->data['voucher'] = $this->request->post['voucher'];

				$json['success'] = $this->language->get('text_success');
			} else {
				unset($this->session->data['voucher']);

				$json['success'] = $this->language->get('text_remove');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function add(): void {
		$this->load->language('api/sale/voucher');

		$json = [];

		// Add keys for missing post vars
		$keys = [
			'from_name',
			'from_email',
			'to_name',
			'to_email',
			'voucher_theme_id',
			'message',
			'amount'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		// Add a new voucher if set
		if ((Helper\Utf8\strlen($this->request->post['from_name']) < 1) || (Helper\Utf8\strlen($this->request->post['from_name']) > 64)) {
			$json['error']['from_name'] = $this->language->get('error_from_name');
		}

		if ((Helper\Utf8\strlen($this->request->post['from_email']) > 96) || !filter_var($this->request->post['from_email'], FILTER_VALIDATE_EMAIL)) {
			$json['error']['from_email'] = $this->language->get('error_email');
		}

		if ((Helper\Utf8\strlen($this->request->post['to_name']) < 1) || (Helper\Utf8\strlen($this->request->post['to_name']) > 64)) {
			$json['error']['to_name'] = $this->language->get('error_to_name');
		}

		if ((Helper\Utf8\strlen($this->request->post['to_email']) > 96) || !filter_var($this->request->post['to_email'], FILTER_VALIDATE_EMAIL)) {
			$json['error']['to_email'] = $this->language->get('error_email');
		}

		if (($this->request->post['amount'] < $this->config->get('config_voucher_min')) || ($this->request->post['amount'] > $this->config->get('config_voucher_max'))) {
			$json['error']['amount'] = sprintf($this->language->get('error_amount'), $this->currency->format($this->config->get('config_voucher_min'), $this->session->data['currency']), $this->currency->format($this->config->get('config_voucher_max'), $this->session->data['currency']));
		}

		if (!$json) {
			$code = Helper\General\token();

			$this->session->data['vouchers'][] = [
				'code'             => $code,
				'description'      => sprintf($this->language->get('text_for'), $this->currency->format($this->currency->convert($this->request->post['amount'], $this->session->data['currency'], $this->config->get('config_currency')), $this->session->data['currency']), $this->request->post['to_name']),
				'to_name'          => $this->request->post['to_name'],
				'to_email'         => $this->request->post['to_email'],
				'from_name'        => $this->request->post['from_name'],
				'from_email'       => $this->request->post['from_email'],
				'voucher_theme_id' => $this->request->post['voucher_theme_id'],
				'message'          => $this->request->post['message'],
				'amount'           => $this->currency->convert($this->request->post['amount'], $this->session->data['currency'], $this->config->get('config_currency'))
			];

			$json['success'] = $this->language->get('text_cart');

			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove(): void {
		$this->load->language('api/sale/cart');

		$json = [];

		if (isset($this->request->post['key'])) {
			$key = (int)$this->request->post['key'];
		} else {
			$key = '';
		}

		if (!isset($this->session->data['vouchers'][$key])) {
			$json['error'] = $this->language->get('error_voucher');
		}

		// Remove
		if (!$json) {
			unset($this->session->data['vouchers'][$key]);

			$json['success'] = $this->language->get('text_success');

			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
