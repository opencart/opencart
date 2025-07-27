<?php
namespace Opencart\Admin\Controller\Task;
/**
 * Class Country
 *
 * @package Opencart\Admin\Controller\Task
 */
class Country extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates the country list JSON files by language.
	 *
	 * @return void
	 */
	public function index($args = []): string {
		$this->load->language('task/country');

		$this->load->model('setting/task');

		// Get all languages so we don't need to keep querying the DB
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		// Get all countries so we don't need to keep querying the DB
		$this->load->model('localisation/country');

		$countries = $this->model_localisation_country->getCountries();

		$sort_order = [];

		foreach ($countries as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $countries);

		foreach ($languages as $language) {
			$country_data = [];

			foreach ($countries as $country) {
				$description_info = $this->model_localisation_country->getDescription($country['country_id'], $language['language_id']);

				if ($description_info) {
					$country_data[$country['country_id']] = $description_info + $country;

					// Add a task for generating the country info data
					$task_data = [
						'code'   => 'country',
						'action' => 'admin/country.info',
						'args'   => $country_data[$country['country_id']]
					];

					$this->model_setting_task->addTask($task_data);
				}
			}

			$base = DIR_APPLICATION . 'view/data/';
			$directory = $language['code'] . '/localisation/';
			$filename = 'country.json';

			if (!oc_directory_create($base . $directory, 0777)) {
				return sprintf($this->language->get('error_directory'), $directory);
			}

			if (!file_put_contents($base . $directory . $filename, json_encode($country_data))) {
				return sprintf($this->language->get('error_file'), $directory . $filename);
			}
		}

		return $this->language->get('text_success');
	}

	public function info($args = []): string {
		$this->load->language('task/country');

		$zone_data = [];

		$this->load->model('localisation/zone');

		$zones = $this->model_localisation_zone->getZonesByCountryId($args['country_id']);

		foreach ($zones as $zone) {
			$zone_description_info = $this->model_localisation_zone->getDescription($zone['zone_id'], $args['language_id']);

			if ($zone_description_info) {
				$zone_data[] = $zone_description_info + $zone;
			}
		}

		$base = DIR_APPLICATION . 'view/data/';
		$directory = $args['code'] . '/localisation/';
		$filename = 'country-' . $args['country_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return sprintf($this->language->get('error_directory'), $directory);
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($args + ['zone' => $zone_data]))) {
			return sprintf($this->language->get('error_file'), $directory . $filename);
		}

		return $this->language->get('text_success');
	}

	public function clear(): void {
		$this->load->language('task/language');

		$json = [];

		//if (!$this->user->hasPermission('modify', 'ssr/admin/language')) {
		//	$json['error'] = $this->language->get('error_permission');
		//}

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

				$files = oc_directory_read($base . $directory, false, '/country\-.+\.json$/');

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
