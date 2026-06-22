<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Geo Zone
 *
 * @package Opencart\Admin\Controller\Event
 */
class GeoZone extends \Opencart\System\Engine\Controller {
	/**
	 * Add Geo Zone
	 *
	 * Generate new tax rate info data by geo zone ID.
	 *
	 * Trigger admin/model/localisation/geo_zone.addGeoZone/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function addGeoZone(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');

		$country_ids = [];

		// Update countries based on geo zones.
		if (isset($args[1]['zone_to_geo_zone']) && is_array($args[1]['zone_to_geo_zone'])) {
			$country_ids = array_unique(array_column($args[1]['zone_to_geo_zone'], 'country_id'));
		}

		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			// Update tax rates based on geo zone
			$task_data = [
				'code'   => 'tax_rate.info.' . $store_id . '.' . $output,
				'action' => 'task/catalog/tax_rate.info',
				'args'   => [
					'geo_zone_id' => $output,
					'store_id'    => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);

			foreach ($country_ids as $country_id) {
				$task_data = [
					'code'   => 'country.info.' . $store_id . '.' . $country_id,
					'action' => 'task/catalog/country.info',
					'args'   => [
						'country_id' => $country_id,
						'store_id'   => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}
	}

	/**
	 * Edit Geo Zone
	 *
	 * Generate new tax rate info data by geo zone ID.
	 *
	 * Trigger admin/model/localisation/zone.editGeoZone/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function editGeoZone(string &$route, array &$args): void {
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$country_ids = [];

		if (isset($args[1]['zone_to_geo_zone'])) {
			$country_ids = array_column($args[1]['zone_to_geo_zone'], 'country_id');
		}

		// Update country info for any removed geo zones
		$this->load->model('localisation/geo_zone');

		$country_ids = array_unique(array_merge(array_column($this->model_localisation_geo_zone->getZones($args[0]), 'country_id'), $country_ids));

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		// Update countries based on geo zones.
		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'tax_rate.info.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/tax_rate.info',
				'args'   => [
					'geo_zone_id' => $args[0],
					'store_id'    => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);

			foreach ($country_ids as $country_id) {
				$task_data = [
					'code'   => 'country.info.' . $store_id . '.' . $country_id,
					'action' => 'task/catalog/country.info',
					'args'   => [
						'country_id' => $country_id,
						'store_id'   => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}
	}

	/**
	 * Delete Geo Zone
	 *
	 * Generate new tax rate info data by geo zone ID.
	 *
	 * Trigger admin/model/localisation/zone.deleteGeoZone/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function deleteGeoZone(string &$route, array &$args, &$output): void {
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		// Update country files based on geo zones.
		$this->load->model('localisation/geo_zone');

		$results = $this->model_localisation_geo_zone->getZones($args[0]);

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		// Update countries based on geo zones.
		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'tax_rate.delete.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/tax_rate.delete',
				'args'   => [
					'geo_zone_id' => $args[0],
				    'store_id'    => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);

			foreach ($results as $result) {
				$task_data = [
					'code'   => 'country.info.' . $store_id . '.' . $result['country_id'],
					'action' => 'task/catalog/country.info',
					'args'   => [
						'country_id' => $result['country_id'],
						'store_id'   => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}
	}
}