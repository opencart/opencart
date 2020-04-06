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
			$this->load->model('extension/total/voucher');

			if (isset($this->request->post['voucher'])) {
				$voucher = $this->request->post['voucher'];
			} else {
				$voucher = '';
			}

			$voucher_info = $this->model_extension_total_voucher->getVoucher($voucher);

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

			if (isset($this->request->post['voucher'])) {
				$this->session->data['vouchers'] = array();

				foreach ($this->request->post['voucher'] as $voucher) {
					if (isset($voucher['code']) && isset($voucher['to_name']) && isset($voucher['to_email']) && isset($voucher['from_name']) && isset($voucher['from_email']) && isset($voucher['voucher_theme_id']) && isset($voucher['message']) && isset($voucher['amount'])) {
						$this->session->data['vouchers'][$voucher['code']] = array(
							'code'             => $voucher['code'],
							'description'      => sprintf($this->language->get('text_for'), $this->currency->format($this->currency->convert($voucher['amount'], $this->session->data['currency'], $this->config->get('config_currency')), $this->session->data['currency']), $voucher['to_name']),
							'to_name'          => $voucher['to_name'],
							'to_email'         => $voucher['to_email'],
							'from_name'        => $voucher['from_name'],
							'from_email'       => $voucher['from_email'],
							'voucher_theme_id' => $voucher['voucher_theme_id'],
							'message'          => $voucher['message'],
							'amount'           => $this->currency->convert($voucher['amount'], $this->session->data['currency'], $this->config->get('config_currency'))
						);
					}
				}

				$json['success'] = $this->language->get('text_cart');

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			} else {
				// Add a new voucher if set
				if ((utf8_strlen($this->request->post['from_name']) < 1) || (utf8_strlen($this->request->post['from_name']) > 64)) {
					$json['error']['from_name'] = $this->language->get('error_from_name');
				}

				if ((utf8_strlen($this->request->post['from_email']) > 96) || !filter_var($this->request->post['from_email'], FILTER_VALIDATE_EMAIL)) {
					$json['error']['from_email'] = $this->language->get('error_email');
				}

				if ((utf8_strlen($this->request->post['to_name']) < 1) || (utf8_strlen($this->request->post['to_name']) > 64)) {
					$json['error']['to_name'] = $this->language->get('error_to_name');
				}

				if ((utf8_strlen($this->request->post['to_email']) > 96) || !filter_var($this->request->post['to_email'], FILTER_VALIDATE_EMAIL)) {
					$json['error']['to_email'] = $this->language->get('error_email');
				}

				if (($this->request->post['amount'] < $this->config->get('config_voucher_min')) || ($this->request->post['amount'] > $this->config->get('config_voucher_max'))) {
					$json['error']['amount'] = sprintf($this->language->get('error_amount'), $this->currency->format($this->config->get('config_voucher_min'), $this->session->data['currency']), $this->currency->format($this->config->get('config_voucher_max'), $this->session->data['currency']));
				}

				if (!$json) {
					$code = token();

					$this->session->data['vouchers'][$code] = array(
						'code'             => $code,
						'description'      => sprintf($this->language->get('text_for'), $this->currency->format($this->currency->convert($this->request->post['amount'], $this->session->data['currency'], $this->config->get('config_currency')), $this->session->data['currency']), $this->request->post['to_name']),
						'to_name'          => $this->request->post['to_name'],
						'to_email'         => $this->request->post['to_email'],
						'from_name'        => $this->request->post['from_name'],
						'from_email'       => $this->request->post['from_email'],
						'voucher_theme_id' => $this->request->post['voucher_theme_id'],
						'message'          => $this->request->post['message'],
						'amount'           => $this->currency->convert($this->request->post['amount'], $this->session->data['currency'], $this->config->get('config_currency'))
					);

					$json['success'] = $this->language->get('text_cart');

					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
