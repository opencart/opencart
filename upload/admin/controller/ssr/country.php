<?php
namespace Opencart\Admin\Controller\Ssr;
/**
 * Class Country
 *
 * @package Opencart\Admin\Controller\Ssr
 */
class Country extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates the country list JSON files by language.
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('ssr/country');

		$json = [];

		if (!$this->user->hasPermission('modify', 'ssr/country')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$directory = DIR_CATALOG . 'view/data/localisation/';

		if (!is_dir($directory) && !mkdir($directory, 0777)) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			// Generate a list of countries to store as JSON
			$countries = [];

			$this->load->model('localisation/country');

			$results = $this->model_localisation_country->getCountries();

			foreach ($results as $result) {
				if ($result['status']) {
					$descriptions = $this->model_localisation_country->getDescriptions($result['country_id']);

					foreach ($descriptions as $description) {
						if (isset($languages[$description['language_id']])) {
							$countries[$languages[$description['language_id']]['code']][] = $description + $result;
						}
					}
				}
			}

			foreach ($countries as $language => $value) {
				$file = $directory . 'country.' . $language . '.json';

				if (!file_put_contents($file, json_encode($value))) {
					$json['error'] = $this->language->get('error_file');

					break;
				}
			}
		}

		if (!$json) {
			$json['text'] = $this->language->get('text_country');

			$json['next'] = $this->url->link('ssr/country.info', 'user_token=' . $this->session->data['user_token'], true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function info() {
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

		$directory = DIR_CATALOG . 'view/data/localisation/';

		if (!is_dir($directory) && !mkdir($directory, 0777)) {
			$json['error'] = sprintf($this->language->get('error_directory'), $directory);
		}

		if (!$json) {
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			$limit = 5;

			$this->load->model('localisation/country');
			$this->load->model('localisation/zone');

			$country_total = $this->model_localisation_country->getTotalCountries();

			$start = ($page - 1) * $limit;
			$end = $start > ($country_total - $limit) ? $country_total : ($start + $limit);

			$filter_data = [
				'start' => $start,
				'limit' => $limit
			];

			$countries = $this->model_localisation_country->getCountries($filter_data);

			foreach ($countries as $country) {
				if ($country['status']) {
					$descriptions = $this->model_localisation_country->getDescriptions($country['country_id']);

					foreach ($descriptions as $description) {
						if (isset($languages[$description['language_id']])) {
							$file = $directory . 'country.' . (int)$country['country_id'] . '.' . $languages[$description['language_id']]['code'] . '.json';

							if (!file_put_contents($file, json_encode($description + $country + ['zone' => $this->model_localisation_zone->getZonesByCountryId($country['country_id'])]))) {
								$json['error'] = sprintf($this->language->get('error_file'), $file);

								break;
							}
						}
					}
				}
			}
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_next'), $start, $end, $country_total);

			if ($end < $country_total) {
				$json['next'] = $this->url->link('ssr/country.info', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
			} else {
				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear() {
		$this->load->language('ssr/language');

		$json = [];

		if (!$this->user->hasPermission('modify', 'ssr/language')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$files = glob(DIR_CATALOG . 'view/data/localisation/country.*.json');

			foreach ($files as $file) {
				unlink($file);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}