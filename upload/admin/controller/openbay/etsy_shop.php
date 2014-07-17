<?php
class ControllerOpenbayEtsyShop extends Controller {
	private $error;

	public function getSections() {
		$shop_sections = $this->openbay->etsy->call('shop/getSections', 'GET');

		$this->response->addHeader('Content-Type: application/json');
		return $this->response->setOutput(json_encode($shop_sections));
	}
}