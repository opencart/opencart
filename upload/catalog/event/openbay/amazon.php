<?php
class EventOpenbayAmazon extends Event {
	public function addOrder($order_id) {
		$this->openbay->amazon->addOrder($order_id);
	}
}