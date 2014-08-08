<?php
class EventOpenbayAmazonus extends Event {
	public function deleteProduct($product_id) {
		$this->openbay->amazonus->deleteProduct($product_id);
	}

	public function editProduct() {
		$this->openbay->amazonus->productUpdateListen($this->request->get['product_id'], $this->request->post);
	}
}