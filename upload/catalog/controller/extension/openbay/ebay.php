<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionOpenbayEbay extends Controller {
	public function eventAddOrder($route, $data) {

	}

	public function eventAddOrderHistory($route, $data) {
		$this->openbay->ebay->log('eventAddOrderHistory Event fired: ' . $route);
		
		if (isset($data[0]) && !empty($data[0])) {
			$this->load->model('extension/openbay/ebay_order');
			
			$this->openbay->ebay->log('Order ID: ' . (int)$data[0]);

			$this->model_extension_openbay_ebay_order->addOrderHistory((int)$data[0]);
		}
	}
}