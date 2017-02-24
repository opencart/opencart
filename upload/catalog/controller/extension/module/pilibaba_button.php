<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionModulePilibabaButton extends Controller {
	public function index() {
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