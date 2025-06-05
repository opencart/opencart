<?php
namespace Opencart\Admin\Controller\Ssr\Catalog;
/**
 * Class Country
 *
 * @package Opencart\Admin\Controller\Ssr\Catalog
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
		$this->load->language('ssr/catalog/country');

		$json = [];

		if (!$this->user->hasPermission('modify', 'ssr/catalog/country')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$stores = [];

			$stores[] = [
				'store_id' => 0,
				'url'      => HTTP_CATALOG
			];

			$this->load->model('setting/store');

			$stores = array_merge($stores, $this->model_setting_store->getStores());

			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			$this->load->model('localisation/country');

			foreach ($stores as $store) {
				$store_url = parse_url($store['url'], PHP_URL_HOST);

				$countries = $this->model_localisation_country->getCountriesByStoreId($store['store_id']);

				foreach ($languages as $language) {
					$country_data = [];

					foreach ($countries as $country) {
						if ($country['status']) {
							$description_info = $this->model_localisation_country->getDescription($country['country_id'], $language['language_id']);

							if ($description_info) {
								$country_data[$country['country_id']] = $description_info + $country;
							}
						}
					}

					$sort_order = [];

					foreach ($country_data as $key => $value) {
						$sort_order[$key] = $value['name'];
					}

					array_multisort($sort_order, SORT_ASC, $country_data);

					$base = DIR_CATALOG . 'view/data/';
					$directory = $store_url . '/' . $language['code'] . '/localisation/';
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
		}

		// Must not have a path before files and directories can be moved
		if (!$json) {
			$json['text'] = $this->language->get('text_list');

			$json['next'] = $this->url->link('ssr/catalog/country.info', 'user_token=' . $this->session->data['user_token'], true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function info() {
		$this->load->language('ssr/catalog/country');

		$json = [];

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->user->hasPermission('modify', 'ssr/catalog/country')) {
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
			$this->load->model('setting/store');

			$countries = $this->model_localisation_country->getCountries($filter_data);

			foreach ($countries as $country) {
				if ($country['status']) {
					$stores = $this->model_localisation_country->getStores($country['country_id']);

					foreach ($stores as $store_id) {
						if (!$store_id) {
							$store_info = [
								'store_id' => 0,
								'url'      => HTTP_CATALOG
							];
						} else {
							$store_info = $this->model_setting_store->getStore($store_id);
						}

						if ($store_info) {
							foreach ($languages as $language) {
								$country_description_info = $this->model_localisation_country->getDescription($country['country_id'], $language['language_id']);

								if (!$country_description_info) {
									continue;
								}

								$base = DIR_CATALOG . 'view/data/';
								$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language['code'] . '/localisation/';
								$filename = 'country-' . $country['country_id'] . '.json';

								if (!oc_directory_create($base . $directory, 0777)) {
									$json['error'] = sprintf($this->language->get('error_directory'), $directory);

									break;
								}

								$zone_data = [];

								$zones = $this->model_localisation_zone->getZonesByCountryId($country['country_id']);

								foreach ($zones as $zone) {
									if ($zone['status']) {
										$zone_description_info = $this->model_localisation_zone->getDescription($zone['zone_id'], $language['language_id']);

										if ($zone_description_info) {
											$zone_data[] = $zone_description_info + $zone;
										}
									}
								}

								if (!file_put_contents($base . $directory . $filename, json_encode($country_description_info + $country + ['zone' => $zone_data]))) {
									$json['error'] = sprintf($this->language->get('error_file'), $directory . $filename);

									break;
								}
							}
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

				$json['next'] = $this->url->link('ssr/catalog/country.info', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
			} else {
				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear() {
		$this->load->language('ssr/catalog/language');

		$json = [];

		if (!$this->user->hasPermission('modify', 'ssr/catalog/language')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('localisation/language');

			$languages = $this->model_localisation_language->getLanguages();

			$stores = [];

			$stores[] = [
				'store_id' => 0,
				'url'      => HTTP_CATALOG
			];

			$this->load->model('setting/store');

			$stores = array_merge($stores, $this->model_setting_store->getStores());

			foreach ($stores as $store) {
				$store_url = parse_url($store['url'], PHP_URL_HOST);

				foreach ($languages as $language) {
					$base = DIR_CATALOG . 'view/data/';
					$directory = $store_url . '/' . $language['code'] . '/localisation/';

					$file = $base . $directory . 'country.json';

					if (is_file($file)) {
						unlink($file);
					}

					$files = glob($base . $directory . 'country-*.json');

					foreach ($files as $file) {
						unlink($file);
					}
				}
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}