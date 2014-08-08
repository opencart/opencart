<?php
class EventOpenbayAmazon extends Event {
	public function deleteProduct($product_id) {
		$this->openbay->amazon->deleteProduct($product_id);
	}

	public function editProduct() {
		$this->openbay->amazon->productUpdateListen($this->request->get['product_id'], $this->request->post);
	}
}