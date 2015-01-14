<?php
class ControllerOpenbayEbay extends Controller {
	public function eventAddOrder($order_id) {
		if (!empty($order_id)) {
			$this->load->model('openbay/ebay_order');

			$this->model_openbay_ebay_order->addOrder($order_id);
		}
	}
}