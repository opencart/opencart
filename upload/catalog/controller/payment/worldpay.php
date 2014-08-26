<?php
class ControllerPaymentWorldPay extends Controller {
	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if (!$this->config->get('worldpay_test')) {
			$data['action'] = 'https://secure.worldpay.com/wcc/purchase';
		} else {
			$data['action'] = 'https://secure-test.worldpay.com/wcc/purchase';
		}

		$data['merchant'] = $this->config->get('worldpay_merchant');
		$data['order_id'] = $order_info['order_id'];
		$data['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$data['currency'] = $order_info['currency_code'];
		$data['description'] = $this->config->get('config_name') . ' - #' . $order_info['order_id'];
		$data['name'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];

		if (!$order_info['payment_address_2']) {
			$data['address'] = $order_info['payment_address_1'] . ', ' . $order_info['payment_city'] . ', ' . $order_info['payment_zone'];
		} else {
			$data['address'] = $order_info['payment_address_1'] . ', ' . $order_info['payment_address_2'] . ', ' . $order_info['payment_city'] . ', ' . $order_info['payment_zone'];
		}

		$data['postcode'] = $order_info['payment_postcode'];
		$data['country'] = $order_info['payment_iso_code_2'];
		$data['telephone'] = $order_info['telephone'];
		$data['email'] = $order_info['email'];
		$data['test'] = $this->config->get('worldpay_test');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/worldpay.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/worldpay.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/worldpay.tpl', $data);
		}
	}

	public function callback() {
		$this->load->language('payment/worldpay');

		$data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

		if (!$this->request->server['HTTPS']) {
			$data['base'] = $this->config->get('config_url');
		} else {
			$data['base'] = $this->config->get('config_ssl');
		}

		$data['language'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

		$data['text_response'] = $this->language->get('text_response');
		$data['text_success'] = $this->language->get('text_success');
		$data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), $this->url->link('checkout/success'));
		$data['text_failure'] = $this->language->get('text_failure');
		$data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), $this->url->link('checkout/checkout', '', 'SSL'));

		if (isset($this->request->post['transStatus']) && $this->request->post['transStatus'] == 'Y') {
			$message = '';

			if (isset($this->request->post['transId'])) {
				$message .= 'transId: ' . $this->request->post['transId'] . "\n";
			}

			if (isset($this->request->post['transStatus'])) {
				$message .= 'transStatus: ' . $this->request->post['transStatus'] . "\n";
			}

			if (isset($this->request->post['countryMatch'])) {
				$message .= 'countryMatch: ' . $this->request->post['countryMatch'] . "\n";
			}

			if (isset($this->request->post['AVS'])) {
				$message .= 'AVS: ' . $this->request->post['AVS'] . "\n";
			}

			if (isset($this->request->post['rawAuthCode'])) {
				$message .= 'rawAuthCode: ' . $this->request->post['rawAuthCode'] . "\n";
			}

			if (isset($this->request->post['authMode'])) {
				$message .= 'authMode: ' . $this->request->post['authMode'] . "\n";
			}

			if (isset($this->request->post['rawAuthMessage'])) {
				$message .= 'rawAuthMessage: ' . $this->request->post['rawAuthMessage'] . "\n";
			}

			if (isset($this->request->post['wafMerchMessage'])) {
				$message .= 'wafMerchMessage: ' . $this->request->post['wafMerchMessage'] . "\n";
			}

			$this->load->model('checkout/order');

			// If returned successful but callbackPW doesn't match, set order to pendind and record reason
			if (isset($this->request->post['callbackPW']) && ($this->request->post['callbackPW'] == $this->config->get('worldpay_password'))) {
				$this->model_checkout_order->addOrderHistory($this->request->post['cartId'], $this->config->get('worldpay_order_status_id'), $message, false);
			} else {
				$this->model_checkout_order->addOrderHistory($this->request->post['cartId'], $this->config->get('config_order_status_id'), $this->language->get('text_pw_mismatch'));
			}

			$data['continue'] = $this->url->link('checkout/success');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/worldpay_success.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/worldpay_success.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/payment/worldpay_success.tpl', $data));
			}
		} else {
			$data['continue'] = $this->url->link('checkout/cart');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/worldpay_failure.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/worldpay_failure.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/payment/worldpay_failure.tpl', $data));
			}
		}
	}
}