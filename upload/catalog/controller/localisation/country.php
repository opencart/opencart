<?php
namespace Opencart\Catalog\Controller\Localisation;
/**
 * Class Country
 *
 * @package Opencart\Catalog\Controller\Localisation
 */
class Country extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$json = [];

		if (isset($this->request->get['country_id'])) {
			$country_id = (int)$this->request->get['country_id'];
		} else {
			$country_id = 0;
		}

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($country_id);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = [
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($country_id),
				'status'            => $country_info['status']
			];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}