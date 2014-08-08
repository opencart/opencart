<?php
class EventOpenbayEtsy extends Event {
	public function deleteProduct($product_id) {
		$this->openbay->etsy->deleteProduct($product_id);
	}

	public function editProduct() {
		$this->openbay->etsy->productUpdateListen($this->request->get['product_id'], $this->request->post);
	}
}