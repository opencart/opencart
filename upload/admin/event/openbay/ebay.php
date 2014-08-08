<?php
class EventOpenbayEbay extends Event {
	public function deleteProduct($product_id) {
		$this->openbay->amazon->ebay($product_id);
	}

	public function editProduct() {
		$this->openbay->ebay->productUpdateListen($this->request->get['product_id'], $this->request->post);
	}
}