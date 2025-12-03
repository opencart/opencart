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

		if (!array_key_exists('zone_to_geo_zone_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Geo Zone
		$this->load->model('localisation/geo_zone');

		$zone_info = $this->model_localisation_geo_zone->getZone((int)$args['zone_to_geo_zone_id']);

		if (!$zone_info) {
			return ['error' => $this->language->get('error_zone')];
		}

		$geo_zone_info = $this->model_localisation_geo_zone->getGeoZone($zone_info['geo_zone_id']);

		if (!$geo_zone_info) {
			return ['error' => $this->language->get('error_geo_zone')];
		}

		$directory = DIR_CATALOG . 'view/data/localisation/';
		$filename = 'geo_zone-' . $zone_info['country_id'] . '-' . $zone_info['zone_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($zone_info + $geo_zone_info))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $geo_zone_info['name'])];
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

		$file = DIR_CATALOG . 'view/data/localisation/tax_class.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
