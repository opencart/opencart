<?php
class EventOpenbayAmazon extends Event {
	public function deleteProduct($product_id) {
		$this->openbay->amazon->deleteProduct($product_id);
	}
}