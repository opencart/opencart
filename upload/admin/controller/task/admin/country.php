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

		$this->load->model('setting/task');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			$task_data = [
				'code'   => 'country',
				'action' => 'task/admin/country.list',
				'args'   => ['language_id' => $language['language_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate JSON country list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/admin/country');

		if (!array_key_exists('language_id', $args)) {
			return ['error' => $this->language->get('error_required', 'language_id')];
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$filter_data = [
			'filter_language_id' => $language_info['language_id'],
			'sort_order'         => 'ASC'
		];

		$this->load->model('localisation/country');

		$countries = $this->model_localisation_country->getCountries($filter_data);

		foreach ($countries as $country) {
			$task_data = [
				'code'   => 'country',
				'action' => 'task/admin/country.info',
				'args'   => [
					'country_id'  => $country['country_id'],
					'language_id' => $language_info['language_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		$sort_order = [];

		foreach ($countries as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $countries);

		$base = DIR_APPLICATION . 'view/data/';
		$directory = $language_info['code'] . '/localisation/';
		$filename = 'country.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => $this->language->get('error_directory', $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($countries))) {
			return ['error' => $this->language->get('error_file', $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list', $language_info['name'])];
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

		$required = [
			'country_id',
			'language_id'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => $this->language->get('error_required', $value)];
			}
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry((int)$args['country_id']);

		if (!$country_info) {
			return ['error' => $this->language->get('error_country')];
		}

		$description_info = $this->model_localisation_country->getDescription((int)$args['country_id'], $language_info['language_id']);

		if (!$description_info) {
			return ['error' => $this->language->get('error_description')];
		}

		$filter_data = [
			'filter_country_id'  => $country_info['country_id'],
			'filter_language_id' => $language_info['language_id']
		];

		$this->load->model('localisation/zone');

		$zones = $this->model_localisation_zone->getZones($filter_data);

		$base = DIR_APPLICATION . 'view/data/';
		$directory = $language_info['code'] . '/localisation/';
		$filename = 'country-' . $country_info['country_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => $this->language->get('error_directory', $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($country_info + ['zone' => $zones]))) {
			return ['error' => $this->language->get('error_file', $directory . $filename)];
		}

		return ['success' => $this->language->get('text_info', $language_info['name'], $country_info['name'])];
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

		return ['success' => $this->language->get('text_clear')];
	}
}
