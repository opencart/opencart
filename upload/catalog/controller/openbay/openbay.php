<?php
class ControllerOpenbayOpenbay extends Controller {
	public function eventAddOrder($data) {
		$this->openbay->orderNew($data['order_id']);
	}

	public function __eventTest() {
		die('test');
	}
}