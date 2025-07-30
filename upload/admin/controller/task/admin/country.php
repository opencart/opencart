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
	 * Generates the country list.
	 *
	 * @return void
	 */
	public function index($args = []): array {
		$this->load->language('task/admin/country');

		$this->load->model('setting/task');

		// Get all languages so we don't need to keep querying the DB
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			// Add a task for generating the country list
			$task_data = [
				'code'   => 'country',
				'action' => 'admin/country.list',
				'args'   => ['language_id' => $language['language_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * List
	 *
	 * Generates the country list.
	 *
	 * @return void
	 */
	public function list(array $args = []): array {
		$this->load->language('task/admin/country');

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$this->load->model('localisation/country');

		$countries = $this->model_localisation_country->getCountries(['filter_language_id' => $language_info['language_id']]);

		$sort_order = [];

		foreach ($countries as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $countries);

		$base = DIR_APPLICATION . 'view/data/';
		$directory = $language_info['code'] . '/localisation/';
		$filename = 'country.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($countries))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		foreach ($countries as $country) {
			// Add a task for generating the country info data
			$task_data = [
				'code'   => 'country',
				'action' => 'admin/country.info',
				'args'   => $country
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_list'), $language_info['name'])];
	}

	/**
	 * Info
	 *
	 * Generates the country information.
	 *
	 * @return void
	 */
	public function info(array $args = []): array {
		$this->load->language('task/admin/country');

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$this->load->model('localisation/zone');

		$zones = $this->model_localisation_zone->getZonesByCountryId($args['country_id'], $language_info['language_id']);

		$base = DIR_APPLICATION . 'view/data/';
		$directory = $language_info['code'] . '/localisation/';
		$filename = 'country-' . $args['country_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($args + ['zone' => $zones]))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $language_info['name'], $args['name'])];
	}

	public function clear(): void {
		$this->load->language('task/admin/country');

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
