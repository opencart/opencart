<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Country
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
		$this->load->language('task/admin/country');

		// Clear old data
		$task_data = [
			'code'   => 'country',
			'action' => 'task/admin/country.clear',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Create new data
		$task_data = [
			'code'   => 'country',
			'action' => 'task/admin/country.list',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		return ['success' => $this->language->get('text_task')];
	}

	public function list(array $args = []): array {
		$this->load->language('task/admin/country');

		$this->load->model('setting/task');

		$country_data = [];

		$this->load->model('localisation/country');

		$countries = $this->model_localisation_country->getCountries(['sort_order' => 'ASC']);

		foreach ($countries as $country) {
			$country_data[] = $country + ['description' => $this->model_localisation_country->getDesciptions($country['country_id'])];

			$task_data = [
				'code'   => 'country',
				'action' => 'task/admin/country.info',
				'args'   => ['country_id' => $country['country_id']]
			];

			$this->model_setting_task->addTask($task_data);
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

		// Description
		$country_info = $country_info + ['description' => $this->model_localisation_country->getDescriptions((int)$country_info['country_id'])];

		// Zones
		$zone_data = [];

		$this->load->model('localisation/zone');

		$zones = $this->model_localisation_zone->getZonesByCountryId($country_info['country_id']);

		foreach ($zones as $zone) {
			$zone_data[] = $zone + ['description' => $this->model_localisation_zone->getDescriptions((int)$zone['zone_id'])];
		}

		$directory = DIR_APPLICATION . 'view/data/localisation/';
		$filename = 'country-' . $country_info['country_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($country_info + ['zone' => $zone_data]))) {
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
	public function clear(array $args = []): array {
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
