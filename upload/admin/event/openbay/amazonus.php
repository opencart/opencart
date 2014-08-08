<?php
class EventOpenbayAmazonus extends Event {
	public function deleteProduct($product_id) {
		$this->openbay->amazonus->deleteProduct($product_id);
	}
}