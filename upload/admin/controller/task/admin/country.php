<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Country
 *
 * Generate country information for the admin.
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class Country extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate country task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/country');

		// 1. Add country list task
		$task_data = [
			'code'   => 'country.list',
			'action' => 'task/catalog/country.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// 2. Add country list task
		$this->load->model('localisation/country');

		$results = $this->model_localisation_country->getCountries(['sort_order' => 'ASC']);

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'country.info.' . $result['country_id'],
				'action' => 'task/catalog/country.info',
				'args'   => ['country_id' => $result['country_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate country list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/admin/country');

		$country_data = [];

		$this->load->model('localisation/country');

		$countries = $this->model_localisation_country->getCountries(['sort_order' => 'ASC']);

		foreach ($countries as $country) {
			$description_data = [];

			$descriptions = $this->model_localisation_country->getDescriptions($country['country_id']);

			foreach ($descriptions as $code => $description) {
				$description_data[$code] = ['name' => $description['name']];
			}

			$country_data[] = [
				'country_id'  => $country['country_id'],
				'description' => $description_data
			];
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

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry((int)$args['country_id']);

		if (!$country_info) {
			return ['error' => $this->language->get('error_country')];
		}

		// Zones
		$zone_data = [];

		$this->load->model('localisation/zone');

		$zones = $this->model_localisation_zone->getZonesByCountryId($country_info['country_id']);

		foreach ($zones as $zone) {
			$zone_data[] = $zone + ['description' => $this->model_localisation_zone->getDescriptions($zone['zone_id'])];
		}


		$country_data = [
			'country_id'        => $country_info['country_id'],
			'description'       => $description_data,
			'iso_code_2'        => $country_info['iso_code_2'],
			'iso_code_3'        => $country_info['iso_code_3'],
			'address_format_id' => $country_info['address_format_id'],
			'postcode_required' => $country_info['postcode_required'],
			'zones'             => $zone_data,
			'geo_zones'         => $geo_zone_data
		];





		$directory = DIR_APPLICATION . 'view/data/localisation/';
		$filename = 'country-' . $country_info['country_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode(array_merge($country_info, ['description' => $this->model_localisation_country->getDescriptions($country_info['country_id'])], ['zone' => $zone_data])))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $country_info['name'])];
	}

	/**
	 * Delete
	 *
	 * Delete generated JSON country files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/admin/country');

		if (!array_key_exists('country_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry((int)$args['country_id']);

		if (!$country_info) {
			return ['error' => $this->language->get('error_country')];
		}

		$file = DIR_APPLICATION . 'view/data/localisation/country-' . $country_info['country_id'] . '.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => sprintf($this->language->get('text_delete'), $country_info['name'])];
	}
}
