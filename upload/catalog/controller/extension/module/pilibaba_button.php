<?php
class ControllerExtensionModulePilibabaButton extends Controller {
	public function index() {
		$status = true;

		if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$status = false;
		}

		if ($status) {
			$data['payment_url'] = $this->url->link('extension/payment/pilibaba/express', 'language=' . $this->config->get('config_language'));

			return $this->load->view('extension/module/pilibaba_button', $data);
		}
	}
}