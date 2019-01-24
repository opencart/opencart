<?php
class ControllerExtensionModulePPButton extends Controller {
	public function index() {
		if ($this->config->get('payment_pp_express_status') == 1) {
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

				$data['payment_pp_express_incontext_disable'] = $this->config->get('payment_pp_express_incontext_disable');

				if ($this->config->get('payment_pp_express_test') == 1) {
					$data['username'] = $this->config->get('payment_pp_express_sandbox_username');
					$data['paypal_environment'] = 'sandbox';
				} else {
					$data['username'] = $this->config->get('payment_pp_express_username');
					$data['paypal_environment'] = 'production';
				}

				$data['payment_url'] = $this->url->link('extension/payment/pp_express/express');

				return $this->load->view('extension/module/pp_button', $data);
			}
		}
	}
}
