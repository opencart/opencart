<?php
class ControllerPaymentPPPayflowIframe extends Controller {
	public function index() {
		$this->load->model('checkout/order');
		$this->load->model('payment/pp_payflow_iframe');
		$this->load->model('localisation/country');
		$this->load->model('localisation/zone');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if ($this->config->get('pp_payflow_iframe_test')) {
			$mode = 'TEST';
		} else {
			$mode = 'LIVE';
		}

		$payflow_url = 'https://payflowlink.paypal.com';

		if ($this->config->get('pp_payflow_iframe_transaction_method') == 'sale') {
			$transaction_type = 'S';
		} else {
			$transaction_type = 'A';
		}

		$secure_token_id = md5($this->session->data['order_id'] . mt_rand() . microtime());

		$this->model_payment_pp_payflow_iframe->addOrder($order_info['order_id'], $secure_token_id);

		$shipping_country = $this->model_localisation_country->getCountry($order_info['shipping_country_id']);
		$shipping_zone = $this->model_localisation_zone->getZone($order_info['shipping_zone_id']);

		$payment_country = $this->model_localisation_country->getCountry($order_info['payment_country_id']);
		$payment_zone = $this->model_localisation_zone->getZone($order_info['payment_zone_id']);

		$url_params = array(
			'TENDER'            => 'C',
			'TRXTYPE'           => $transaction_type,
			'AMT'               => $this->currency->format($order_info['total'], $order_info['currency_code'], false, false),
			'CURRENCY'          => $order_info['currency_code'],
			'CREATESECURETOKEN' => 'Y',
			'SECURETOKENID'     => $secure_token_id,
			'BILLTOFIRSTNAME'   => $order_info['payment_firstname'],
			'BILLTOLASTNAME'    => $order_info['payment_lastname'],
			'BILLTOSTREET'      => trim($order_info['payment_address_1'] . ' ' . $order_info['payment_address_2']),
			'BILLTOCITY'        => $order_info['payment_city'],
			'BILLTOSTATE'       => $payment_zone['code'],
			'BILLTOZIP'         => $order_info['payment_postcode'],
			'BILLTOCOUNTRY'     => $payment_country['iso_code_2'],
		);

		if ($shipping_country) {
			$url_params['SHIPTOFIRSTNAME'] = $order_info['shipping_firstname'];
			$url_params['SHIPTOLASTNAME'] = $order_info['shipping_lastname'];
			$url_params['SHIPTOSTREET'] = trim($order_info['shipping_address_1'] . ' ' . $order_info['shipping_address_2']);
			$url_params['SHIPTOCITY'] = $order_info['shipping_city'];
			$url_params['SHIPTOSTATE'] = $shipping_zone['code'];
			$url_params['SHIPTOZIP'] = $order_info['shipping_postcode'];
			$url_params['SHIPTOCOUNTRY'] = $shipping_country['iso_code_2'];
		}

		$response_params = $this->model_payment_pp_payflow_iframe->call($url_params);

		if (isset($response_params['SECURETOKEN'])) {
			$secure_token = $response_params['SECURETOKEN'];
		} else {
			$secure_token = '';
		}

		$iframe_params = array(
			'MODE'          => $mode,
			'SECURETOKENID' => $secure_token_id,
			'SECURETOKEN'   => $secure_token,
		);

		$data['iframe_url'] = $payflow_url . '?' . http_build_query($iframe_params, '', "&");
		$data['checkout_method'] = $this->config->get('pp_payflow_iframe_checkout_method');
		$data['button_confirm'] = $this->language->get('button_confirm');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pp_payflow_iframe.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/pp_payflow_iframe.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/pp_payflow_iframe.tpl', $data);
		}
	}

	public function paymentReturn() {
		$data['url'] = $this->url->link('checkout/success');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pp_payflow_iframe_return.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/pp_payflow_iframe_return.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/payment/pp_payflow_iframe_return.tpl', $data));
		}
	}

	public function paymentCancel() {
		$data['url'] = $this->url->link('checkout/checkout');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pp_payflow_iframe_return.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/pp_payflow_iframe_return.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/payment/pp_payflow_iframe_return.tpl', $data));
		}
	}

	public function paymentError() {
		$data['url'] = $this->url->link('checkout/checkout');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pp_payflow_iframe_return.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/pp_payflow_iframe_return.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/payment/pp_payflow_iframe_return.tpl', $data));
		}
	}

	public function paymentIpn() {
		$this->load->model('payment/pp_payflow_iframe');
		$this->load->model('checkout/order');

		if ($this->config->get('pp_pro_iframe_debug')) {
			$log = new Log('pp_pro_iframe.log');
			$log->write('POST: ' . print_r($this->request->post, 1));
		}
						
		$order_id = $this->model_payment_pp_payflow_iframe->getOrderId($this->request->post['SECURETOKENID']);

		if ($order_id) {
			$order_info = $this->model_checkout_order->getOrder($order_id);

			$url_params = array(
				'TENDER'  => 'C',
				'TRXTYPE' => 'I',
				'ORIGID'  => $this->request->post['PNREF'],
			);

			$response_params = $this->model_payment_pp_payflow_iframe->call($url_params);

			if ($order_info['order_status_id'] == 0 && $response_params['RESULT'] == '0' && $this->request->post['RESULT'] == 0) {
				$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('pp_payflow_iframe_order_status_id'));

				if ($this->request->post['TYPE'] == 'S') {
					$complete = 1;
				} else {
					$complete = 0;
				}

				$data = array(
					'secure_token_id'       => $this->request->post['SECURETOKENID'],
					'transaction_reference' => $this->request->post['PNREF'],
					'transaction_type'      => $this->request->post['TYPE'],
					'complete'              => $complete,
				);

				$this->model_payment_pp_payflow_iframe->updateOrder($data);

				$data = array(
					'order_id'              => $order_id,
					'type'                  => $this->request->post['TYPE'],
					'transaction_reference' => $this->request->post['PNREF'],
					'amount'                => $this->request->post['AMT'],
				);

				$this->model_payment_pp_payflow_iframe->addTransaction($data);
			}
		}

		$this->response->setOutput('Ok');
	}
}