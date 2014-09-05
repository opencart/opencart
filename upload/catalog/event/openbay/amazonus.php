<?php
class EventOpenbayAmazonus extends Event {
	public function addOrder($order_id) {
		$this->openbay->amazonus->addOrder($order_id);
	}
}