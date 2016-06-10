<?php
class ControllerExtensionModulePPButton extends Controller {
	public function index() {
		$status = true;

		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout')) || (!$this->customer->isLogged() && ($this->cart->hasRecurringProducts() || $this->cart->hasDownload()))) {
			$status = false;
		}

		if ($status) {
			$this->load->model('extension/payment/pp_express');

			if (preg_match('/Mobile|Android|BlackBerry|iPhone|Windows Phone/', $this->request->server['HTTP_USER_AGENT'])) {
				$data['mobile'] = true;
			} else {
				$data['mobile'] = false;
			}

			$data['payment_url'] = $this->url->link('extension/payment/pp_express/express', '', true);

			return $this->load->view('extension/module/pp_button', $data);
		}
	}
}