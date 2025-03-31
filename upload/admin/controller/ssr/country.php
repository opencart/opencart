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

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->user->hasPermission('modify', 'ssr/country')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			// Country
			$limit = 5;

			$this->load->model('localisation/country');

			$country_total = $this->model_localisation_country->getTotalCountries();

			$start = ($page - 1) * $limit;
			$end = $start > ($country_total - $limit) ? $country_total : ($start + $limit);

			// Zone
			$this->load->model('localisation/zone');

			$countries = $this->model_localisation_country->getCountries();

			foreach ($countries as $country) {
				//print_r($country);

				if ($country['status']) {
					$descriptions = $this->model_localisation_country->getDescriptions($country['country_id']);

					foreach ($descriptions as $description) {
						print_r($languages);

						print_r($description + $country);

						if (isset($languages[$description['language_id']])) {
							$code = preg_replace('/[^A-Z0-9_-]/i', '', $languages[$description['language_id']]['code']);

							$file = DIR_CATALOG . 'view/data/localisation/country.' . (int)$country['country_id'] . '.' . $code . '.json';

							if (!file_put_contents($file, json_encode($description + $country + ['zone' => $this->model_localisation_zone->getZonesByCountryId($country['country_id'])]))) {
								$json['error'] = $this->language->get('error_file');
							}
						}
					}
				}
			}
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_country'), $start, $end, $country_total);

			if ($end < $country_total) {
				$json['next'] = $this->url->link('ssr/country', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
			} else {
				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}