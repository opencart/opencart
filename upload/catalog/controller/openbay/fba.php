<?php
class ControllerOpenbayFba extends Controller {
	public function eventAddOrderHistory($order_id) {
		if (!empty($order_id)) {
			$this->load->model('openbay/fba_order');

			$this->model_openbay_fba_order->addOrderHistory($order_id);
		}
	}
}