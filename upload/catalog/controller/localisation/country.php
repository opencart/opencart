<?php
namespace Opencart\Catalog\Controller\Localisation;
/**
 * Class Country
 *
 * @package Opencart\Catalog\Controller\Localisation
 */
class Country extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$json = [];

		// Country
		if (isset($this->request->get['country_id'])) {
			$country_id = (int)$this->request->get['country_id'];
		} else {
			$country_id = 0;
		}

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($country_id);

		// Zones
		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = ['zone' => $this->model_localisation_zone->getZonesByCountryId($country_id)] + $country_info;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
