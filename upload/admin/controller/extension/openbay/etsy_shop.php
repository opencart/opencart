<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionOpenbayEtsyShop extends Controller {
	public function getSections() {
		$response = $this->openbay->etsy->call('v1/etsy/shop/getSections/', 'GET');

		$this->response->addHeader('Content-Type: application/json');
		return $this->response->setOutput(json_encode($response));
	}
}