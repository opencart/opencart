<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Country
 *
 * Generates country data for the admin.
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class Country extends \Opencart\System\Engine\Controller {
	/**
	 * List
	 *
	 * Generate country list task for each store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function generateList(array $args = []): array {
		$this->load->language('task/admin/country');

		$this->load->model('setting/task');

		// Language
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		foreach ($languages as $language) {
			$task_data = [
				'code'   => 'country.list.' . $language['language_id'],
				'action' => 'task/catalog/country.list',
				'args'   => ['language_id' => $language['language_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate country list task for each store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/admin/country');

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info || !$language_info['status']) {
			return ['error' => $this->language->get('error_language')];
		}

		$country_data = [];

		$this->load->model('localisation/country');

		$countries = $this->model_localisation_country->getCountries(['sort_order' => 'ASC']);

		foreach ($countries as $country) {
			$description_info = $this->model_localisation_country->getDescription($country['country_id'], $language_info['language_id']);

			if (!$description_info) {
				continue;
			}

			$country_data[] = $description_info + $country;
		}

		$sort_order = [];

		foreach ($country_data as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $country_data);

		$directory = DIR_APPLICATION . 'view/data/localisation/';
		$filename = 'country.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($country_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list')];
	}

	/**
	 * Info
	 *
	 * Generate JSON country information file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/admin/country');

		if (!array_key_exists('country_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Country
		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry((int)$args['country_id']);

		if (!$country_info) {
			return ['error' => $this->language->get('error_country')];
		}

		$this->load->model('setting/task');

		// Language
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages((int)$args['language_id']);

		foreach ($languages as $language) {
			$task_data = [
				'code'   => 'country._info.' . $language['language_id'] . '.' . $country_info['country_id'],
				'action' => 'task/catalog/country.info',
				'args'   => [
					'country_id'  => $country_info['country_id'],
					'language_id' => $language['language_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_info'), $country_info['name'])];
	}

	public function _info(array $args = []): array {
		$this->load->language('task/admin/country');

		if (!array_key_exists('country_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_country')];
		}

		// Country
		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry((int)$args['country_id']);

		if (!$country_info) {
			return ['error' => $this->language->get('error_country')];
		}

		// Description
		$description_info = $this->model_localisation_country->getDescription($country_info['country_id'], $language_info['language_id']);

		if (!$description_info) {
			return ['error' => $this->language->get('error_description')];
		}

		// Zones
		$zone_data = [];

		$this->load->model('localisation/zone');

		$zones = $this->model_localisation_zone->getZonesByCountryId($country_info['country_id']);

		foreach ($zones as $zone) {
			$zone_data[] = $zone + ['description' => $this->model_localisation_zone->getDescription($zone['zone_id'], $language_info['language_id'])];
		}

		$directory = DIR_APPLICATION . 'view/data/localisation/';
		$filename = 'country-' . $country_info['country_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($description_info + $country_info + ['zone' => $zone_data]))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $country_info['name'])];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON country files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/admin/country');

		$directory = DIR_APPLICATION . 'view/data/localisation/';
		$file = $directory . 'country.json';

		if (is_file($file)) {
			unlink($file);
		}

		$files = oc_directory_read($directory, false, '/country-\d+\.json$/');

		foreach ($files as $file) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
