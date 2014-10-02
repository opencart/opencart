<?php
class ControllerModuleAmazonCheckoutLayout extends Controller {
	public function index($setting) {
		if ($this->config->get('amazon_checkout_status') == 1 && $setting['status']) {
			$allowed_ips = $this->config->get('amazon_checkout_allowed_ips');

			if ((empty($allowed_ips) || in_array($this->request->server['REMOTE_ADDR'], $allowed_ips)) && $this->cart->hasProducts()
					&& (!isset($this->session->data['vouchers']) || empty($this->session->data['vouchers'])) && !$this->cart->hasRecurringProducts()) {

				if ($this->config->get('amazon_checkout_mode') == 'sandbox') {
					if ($this->config->get('amazon_checkout_marketplace') == 'uk') {
						$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/gb/sandbox/PaymentWidgets.js';
					} elseif ($this->config->get('amazon_checkout_marketplace') == 'de') {
						$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/de/sandbox/PaymentWidgets.js';
					}
				} elseif ($this->config->get('amazon_checkout_mode') == 'live') {
					if ($this->config->get('amazon_checkout_marketplace') == 'uk') {
						$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/gb/PaymentWidgets.js';
					} elseif ($this->config->get('amazon_checkout_marketplace') == 'de') {
						$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/de/PaymentWidgets.js';
					}
				}

				$this->document->addScript($amazon_payment_js);

				$data['amazon_checkout'] = $this->url->link('payment/amazon_checkout/address', '', 'SSL');
				$data['amazon_checkout_status'] = true;
				$data['merchant_id'] = $this->config->get('amazon_checkout_merchant_id');
				$data['button_colour'] = $this->config->get('amazon_checkout_button_colour');
				$data['button_background'] = $this->config->get('amazon_checkout_button_background');
				$data['button_size'] = $this->config->get('amazon_checkout_button_size');

				$data['layout_id'] = $setting['layout_id'];
				$data['position'] = $setting['position'];

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/amazon_button.tpl')) {
					return $this->load->view($this->config->get('config_template') . '/template/module/amazon_button.tpl', $data);
				} else {
					return $this->load->view('default/template/module/amazon_button.tpl', $data);
				}
			}
		}
	}
}