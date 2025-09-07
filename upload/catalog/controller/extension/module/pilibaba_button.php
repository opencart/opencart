<?php
class ControllerExtensionModulePilibabaButton extends Controller {
	public function index() {
		$this->load->language('extension/module/pilibaba_button');
		$status = true;

		if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$status = false;
		}

		if ($status) {
			$data['payment_url'] = $this->url->link('extension/payment/pilibaba/express', '', true);

			return $this->load->view('extension/module/pilibaba_button', $data);
		}
	}
}
