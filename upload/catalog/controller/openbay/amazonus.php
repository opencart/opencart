<?php
class ControllerOpenbayAmazonus extends Controller {
	public function eventAddOrder($order_id) {
		$this->openbay->amazonus->addOrder($order_id);
	}
}