<?php
class ControllerOpenbayEbay extends Controller {
	public function eventAddOrder($order_id) {
		$this->openbay->ebay->addOrder($order_id);
	}
}