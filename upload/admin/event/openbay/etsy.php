<?php
class EventOpenbayEtsy extends Event {
	public function deleteProduct($product_id) {
		$this->openbay->etsy->deleteProduct($product_id);
	}
}