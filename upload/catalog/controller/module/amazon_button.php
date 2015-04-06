<?php
class ControllerModuleAmazonButton extends Controller {
	public function index($setting) {
		static $module = 0;

		$status = $this->config->get('amazon_checkout_status');

		if ($this->config->get('amazon_checkout_allowed_ips') && !in_array($this->request->server['REMOTE_ADDR'], $this->config->get('amazon_checkout_allowed_ips'))) {
			$status = false;

		} elseif (!$this->cart->hasProducts() || !empty($this->session->data['vouchers']) || $this->cart->hasRecurringProducts()) {
			$status = false;
		}

		if ($status) {
			if ($this->config->get('amazon_checkout_mode') == 'sandbox') {
				if ($this->config->get('amazon_checkout_marketplace') == 'uk') {
					$this->document->addScript('https://static-eu.payments-amazon.com/cba/js/gb/sandbox/PaymentWidgets.js');
				} elseif ($this->config->get('amazon_checkout_marketplace') == 'de') {
					$this->document->addScript('https://static-eu.payments-amazon.com/cba/js/de/sandbox/PaymentWidgets.js');
				}
			} elseif ($this->config->get('amazon_checkout_mode') == 'live') {
				if ($this->config->get('amazon_checkout_marketplace') == 'uk') {
					$this->document->addScript('https://static-eu.payments-amazon.com/cba/js/gb/PaymentWidgets.js');
				} elseif ($this->config->get('amazon_checkout_marketplace') == 'de') {
					$this->document->addScript('https://static-eu.payments-amazon.com/cba/js/de/PaymentWidgets.js');
				}
			}

			$data['merchant_id'] = $this->config->get('amazon_checkout_merchant_id');

			$data['amazon_checkout'] = $this->url->link('payment/amazon_checkout/address', '', 'SSL');

			$data['button_colour'] = $this->config->get('amazon_checkout_button_colour');
			$data['button_background'] = $this->config->get('amazon_checkout_button_background');
			$data['button_size'] = $this->config->get('amazon_checkout_button_size');

			$data['align'] = $setting['align'];

			$data['module'] = $module++;

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/amazon_button.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/amazon_button.tpl', $data);
			} else {
				return $this->load->view('default/template/module/amazon_button.tpl', $data);
			}
		}
	}
}