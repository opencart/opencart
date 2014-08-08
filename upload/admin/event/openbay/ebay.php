<?php
class EventOpenbayEbay extends Event {
	public function deleteProduct($product_id) {
		$this->openbay->amazon->ebay($product_id);
	}
}