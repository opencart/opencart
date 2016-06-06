<?php
class ControllerModulePilibabaButton extends Controller {
	public function index() {
		$status = true;

		if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$status = false;
		}

		if ($status) {
			$data['payment_url'] = $this->url->link('payment/pilibaba/express', '', true);

			return $this->load->view('module/pilibaba_button', $data);
		}
	}
}