<?php
class ControllerApiVoucher extends Controller {
	public function index() {
		$this->load->language('api/voucher');

		// Delete past voucher in case there is an error
		unset($this->session->data['voucher']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('checkout/voucher');

			if (isset($this->request->post['voucher'])) {
				$voucher = $this->request->post['voucher'];
			} else {
				$voucher = '';
			}

			$voucher_info = $this->model_checkout_voucher->getVoucher($voucher);

			if ($voucher_info) {
				$this->session->data['voucher'] = $this->request->post['voucher'];

				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_voucher');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function add() {
		$this->load->language('api/voucher');

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error']['warning'] = $this->language->get('error_permission');
		} else {
			// Add keys for missing post vars
			$keys = array(
				'from_name',
				'from_email',
				'to_name',
				'to_email',
				'voucher_theme_id',
				'message',
				'amount'
			);

			foreach ($keys as $key) {
				if (!isset($this->request->post[$key])) {
					$this->request->post[$key] = '';
				}
			}

			// Add a new voucher if set
			if ((utf8_strlen($this->request->post['from_name']) < 1) || (utf8_strlen($this->request->post['from_name']) > 64)) {
				$json['error']['from_name'] = $this->language->get('error_from_name');
			}

			if ((utf8_strlen($this->request->post['from_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['from_email'])) {
				$json['error']['from_email'] = $this->language->get('error_email');
			}

			if ((utf8_strlen($this->request->post['to_name']) < 1) || (utf8_strlen($this->request->post['to_name']) > 64)) {
				$json['error']['to_name'] = $this->language->get('error_to_name');
			}

			if ((utf8_strlen($this->request->post['to_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['to_email'])) {
				$json['error']['to_email'] = $this->language->get('error_email');
			}

			if (($this->request->post['amount'] < $this->config->get('config_voucher_min')) || ($this->request->post['amount'] > $this->config->get('config_voucher_max'))) {
				$json['error']['amount'] = sprintf($this->language->get('error_amount'), $this->currency->format($this->config->get('config_voucher_min')), $this->currency->format($this->config->get('config_voucher_max')));
			}

			if (!$json) {
				$code = mt_rand();

				$this->session->data['vouchers'][$code] = array(
					'code'             => $code,
					'description'      => sprintf($this->language->get('text_for'), $this->currency->format($this->currency->convert($this->request->post['amount'], $this->currency->getCode(), $this->config->get('config_currency'))), $this->request->post['to_name']),
					'to_name'          => $this->request->post['to_name'],
					'to_email'         => $this->request->post['to_email'],
					'from_name'        => $this->request->post['from_name'],
					'from_email'       => $this->request->post['from_email'],
					'voucher_theme_id' => $this->request->post['voucher_theme_id'],
					'message'          => $this->request->post['message'],
					'amount'           => $this->currency->convert($this->request->post['amount'], $this->currency->getCode(), $this->config->get('config_currency'))
				);

				$json['success'] = $this->language->get('text_cart');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}