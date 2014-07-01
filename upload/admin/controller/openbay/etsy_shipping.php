<?php
class ControllerOpenbayEtsyShipping extends Controller {
	public function getAll() {
		$shipping_templates = $this->openbay->etsy->call('product/shipping/getAllTemplates', 'GET');

		return $this->response->setOutput(json_encode($shipping_templates));
	}
}