<?php
class ControllerOpenbayEtsyShop extends Controller {
	private $error;

	public function getSections() {
		$shop_sections = $this->openbay->etsy->call('shop/getSections', 'GET');

		return $this->response->setOutput(json_encode($shop_sections));
	}
}