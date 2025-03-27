<?php
namespace Opencart\Admin\Controller\Ssr;
/**
 * Class Country
 *
 * @package Opencart\Admin\Controller\Ssr
 */
class Country extends \Opencart\System\Engine\Controller {
	/**
	 * Generate
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('ssr/country');

		$json = [];

		if (!$this->user->hasPermission('modify', 'ssr/country')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Country
			$this->load->model('localisation/country');

			// Zone
			$this->load->model('localisation/zone');

			$results = $this->model_localisation_country->getCountries();

			foreach ($results as $result) {
				if ($result['status']) {
					$file = DIR_CATALOG . 'view/data/localisation/country.' . $result['country_id'] . '.json';

					if (!file_put_contents($file, json_encode(['zone' => $this->model_localisation_zone->getZonesByCountryId($result['country_id'])] + $result))) {
						$json['error'] = $this->language->get('error_file');
					}
				}
			}




			$output = json_encode($results);

			$file = DIR_CATALOG . 'view/data/localisation/country.json';

			if (file_put_contents($file, $output)) {
				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_file');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}