<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Geo Zone
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class GeoZone extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate geo zone class task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/geo_zone');

		$this->load->model('setting/task');

		$this->load->model('localisation/geo_zone');

		$geo_zones = $this->model_localisation_geo_zone->getZones();

		foreach ($geo_zones as $geo_zone) {
			$task_data = [
				'code'   => 'geo_zone',
				'action' => 'task/catalog/geo_zone.info',
				'args'   => ['geo_zone_id' => $geo_zone['geo_zone_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * Info
	 *
	 * Generate geo zone information.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/geo_zone');

		if (!array_key_exists('geo_zone_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Store
		$this->load->model('localisation/geo_zone');

		$geo_zone_info = $this->model_localisation_geo_zone->getGeoZone($args['geo_zone_id']);

		if (!$geo_zone_info) {
			return ['error' => $this->language->get('error_store')];
		}

		// Custom Fields

		$this->load->model('localisation/tax_rate');

		$custom_fields = $this->model_localisation_tax_rate->getZones($filter_data);




		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/customer/';
		$filename = 'customer_group-' . $customer_group_info['customer_group_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($customer_group_info + $description_info + ['custom_field' => $custom_fields]))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $language_info['name'], $customer_group_info['name'])];
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
		$this->load->language('task/catalog/tax_class');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$file = DIR_CATALOG . 'view/data/' . parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/localisation/tax_class.json';

				if (is_file($file)) {
					unlink($file);
				}
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
