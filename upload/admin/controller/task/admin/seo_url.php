<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class SEO URL
 *
 * Generates SEO URL's
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class SeoUrl extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates country task list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/seo_url');

		$this->load->model('setting/task');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {

				$task_data = [
					'code'   => 'seo_url',
					'action' => 'task/admin/seo_url.category',
					'args'   => [
						'store_id'    => $store['store_id'],
						'language_id' => $language['language_id']
					]
				];

				$task_data = [
					'code'   => 'seo_url',
					'action' => 'task/admin/seo_url.information',
					'args'   => [
						'store_id'    => $store['store_id'],
						'language_id' => $language['language_id']
					]
				];

				$task_data = [
					'code'   => 'seo_url',
					'action' => 'task/admin/seo_url.manufacturer',
					'args'   => [
						'store_id'    => $store['store_id'],
						'language_id' => $language['language_id']
					]
				];

				$task_data = [
					'code'   => 'seo_url',
					'action' => 'task/admin/seo_url.product',
					'args'   => [
						'store_id'    => $store['store_id'],
						'language_id' => $language['language_id']
					]
				];


				$task_data = [
					'code'   => 'topic',
					'action' => 'task/admin/seo_url.topic',
					'args'   => [
						'store_id'    => $store['store_id'],
						'language_id' => $language['language_id']
					]
				];

				$this->model_setting_task->addTask($task_data);






				$task_data = [
					'code'   => 'seo_url',
					'action' => 'task/admin/seo_url.product',
					'args'   => [
						'store_id'    => $store['store_id'],
						'language_id' => $language['language_id']
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * List
	 *
	 * Generates the country list file.
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/admin/country');

		$required = [
			'store_id',
			'language_id'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => sprintf($this->language->get('error_required'), $value)];
			}
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$this->load->model('localisation/country');

		$countries = $this->model_localisation_country->getCountries(['sort_order' => 'ASC']);

		foreach ($countries as $country) {
			$description_info = $this->model_localisation_country->getDescription($country['country_id'], $language_info['language_id']);

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
		$directory = $language_info['code'] . '/localisation/';
		$filename = 'country.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($country_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $language_info['name'])];
	}

	/**
	 * Info
	 *
	 * Generates country information.
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/admin/country');

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry((int)$args['country_id']);

		if (!$country_info) {
			return ['error' => $this->language->get('error_country')];
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$description_info = $this->model_localisation_country->getDescription($country_info['country_id'], $language_info['language_id']);

		if (!$description_info) {
			return ['error' => $this->language->get('error_country')];
		}

		$zone_data = [];

		$this->load->model('localisation/zone');

		$zones = $this->model_localisation_zone->getZonesByCountryId((int)$country_info['country_id']);

		foreach ($zones as $zone) {
			$description_info = $this->model_localisation_zone->getDescription((int)$zone['zone_id'], (int)$language_info['language_id']);

			if ($description_info) {
				$zone_data[] = $description_info + $zone;
			}
		}

		$base = DIR_APPLICATION . 'view/data/';
		$directory = $language_info['code'] . '/localisation/';
		$filename = 'country-' . $country_info['country_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($country_info + $description_info + ['zone' => $zone_data]))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $language_info['name'], $country_info['name'])];
	}

	/**
	 * Clear
	 *
	 * Clears generated country files.
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
