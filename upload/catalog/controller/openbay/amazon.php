<?php
class ControllerOpenbayAmazon extends Controller {
	public function eventAddOrder($order_id) {
		$this->openbay->amazon->addOrder($order_id);
	}
}