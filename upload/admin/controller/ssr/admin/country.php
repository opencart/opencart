<?php
namespace Opencart\Admin\Controller\Ssr\Admin;
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
	public function index() {
		$this->load->language('ssr/admin/country');

		$json = [];

		if (!$this->user->hasPermission('modify', 'ssr/admin/country')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Get all languages so we don't need to keep querying te DB
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			$this->load->model('localisation/country');

			$countries = $this->model_localisation_country->getCountries();

			foreach ($languages as $language) {
				$country_data = [];

				foreach ($countries as $country) {
					$description_info = $this->model_localisation_country->getDescription($country['country_id'], $language['language_id']);

					if ($description_info) {
						$country_data[$country['country_id']] = $description_info + $country;
					}
				}

				$sort_order = [];

				foreach ($country_data as $key => $value) {
					$sort_order[$key] = $value['name'];
				}

				array_multisort($sort_order, SORT_ASC, $country_data);

				$base = DIR_APPLICATION . 'view/data/';
				$directory = $language['code'] . '/localisation/';
				$filename = 'country.json';

				if (!oc_directory_create($base . $directory, 0777)) {
					$json['error'] = sprintf($this->language->get('error_directory'), $directory);

					break;
				}

				if (!file_put_contents($base . $directory . $filename, json_encode($country_data))) {
					$json['error'] = sprintf($this->language->get('error_file'), $directory . $filename);

					break;
				}
			}
		}

		// Must not have a path before files and directories can be moved
		if (!$json) {
			$json['text'] = $this->language->get('text_list');

			$json['next'] = $this->url->link('ssr/admin/country.info', 'user_token=' . $this->session->data['user_token'], true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function info() {
		$this->load->language('ssr/admin/country');

		$json = [];

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->user->hasPermission('modify', 'ssr/admin/country')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			$limit = 10;
			$start = ($page - 1) * $limit;

			$filter_data = [
				'start' => $start,
				'limit' => $limit
			];

			$this->load->model('localisation/country');
			$this->load->model('localisation/zone');

			$countries = $this->model_localisation_country->getCountries($filter_data);

			foreach ($countries as $country) {
				foreach ($languages as $language) {
					$description_info = $this->model_localisation_country->getDescription($country['country_id'], $language['language_id']);

					if ($description_info) {
						$base = DIR_APPLICATION . 'view/data/';
						$directory = $language['code'] . '/localisation/';
						$filename = 'country-' . $country['country_id'] . '.json';

						if (!oc_directory_create($base . $directory, 0777)) {
							$json['error'] = sprintf($this->language->get('error_directory'), $directory);

							break;
						}

						if (!file_put_contents($base . $directory . $filename, json_encode($description_info + $country + ['zone' => $this->model_localisation_zone->getZonesByCountryId($country['country_id'])]))) {
							$json['error'] = sprintf($this->language->get('error_file'), $directory . $filename);

							break;
						}
					}
				}
			}
		}

		if (!$json) {
			$country_total = $this->model_localisation_country->getTotalCountries();

			$end = $start > ($country_total - $limit) ? $country_total : ($start + $limit);

			if ($end < $country_total) {
				$json['text'] = sprintf($this->language->get('text_next'), !$start ?? 1, $end, $country_total);

				$json['next'] = $this->url->link('ssr/admin/country.info', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
			} else {
				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear() {
		$this->load->language('ssr/admin/language');

		$json = [];

		if (!$this->user->hasPermission('modify', 'ssr/admin/language')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			foreach ($languages as $language) {
				$base = DIR_APPLICATION . 'view/data/';
				$directory = $language['code'] . '/localisation/';

				$file = $base . $directory . 'country.json';

				if (is_file($file)) {
					unlink($file);
				}

				$files = glob($base . $directory . 'country-*.json');

				foreach ($files as $file) {
					unlink($file);
				}
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}