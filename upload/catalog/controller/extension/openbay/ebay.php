<?php
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