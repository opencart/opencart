<?php
class EventOpenbayEtsy extends Event {
	public function addOrder($order_id) {
		$this->openbay->etsy->addOrder($order_id);
	}
}