<?php
class EventOpenbayOpenbay extends Event {
	public function deleteProduct($product_id) {
		foreach ($this->openbay->installed_markets as $market) {
			if ($this->config->get($market . '_status') == 1) {
				$this->openbay->{$market}->deleteProduct($product_id);
			}
		}
	}

	public function editProduct() {
		foreach ($this->openbay->installed_markets as $market) {
			if ($this->config->get($market . '_status') == 1) {
				$this->openbay->{$market}->productUpdateListen($this->request->get['product_id'], $this->request->post);
			}
		}
	}
}