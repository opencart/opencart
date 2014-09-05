<?php
class EventOpenbayEbay extends Event {
	public function addOrder($order_id) {
		$this->openbay->ebay->addOrder($order_id);
	}
}