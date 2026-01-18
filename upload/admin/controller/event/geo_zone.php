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
	 * Called using admin/model/localisation/geo_zone.addGeoZone/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function addGeoZone(string &$route, array &$args, &$output): void {
		// Update tax rates based on geo zone
		$task_data = [
			'code'   => 'tax_rate.info.' . $output,
			'action' => 'task/catalog/tax_rate.info',
			'args'   => ['geo_zone_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Update countries based on geo zones.
		if (isset($args[1]['zone_to_geo_zone']) && is_array($args[1]['zone_to_geo_zone'])) {
			$country_ids = array_unique(array_column($args[1]['zone_to_geo_zone'], 'country_id'));

			foreach ($country_ids as $country_id) {
				$task_data = [
					'code'   => 'country.info.' . $country_id,
					'action' => 'task/catalog/country.info',
					'args'   => ['country_id' => $country_id]
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
	 * Called using admin/model/localisation/zone.editGeoZone/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function editGeoZone(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'tax_rate.info.' . $args[0],
			'action' => 'task/catalog/tax_rate.info',
			'args'   => ['geo_zone_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Update countries based on geo zones.
		if (isset($args[1]['zone_to_geo_zone']) && is_array($args[1]['zone_to_geo_zone'])) {
			$country_ids = array_unique(array_column($args[1]['zone_to_geo_zone'], 'country_id'));

			foreach ($country_ids as $country_id) {
				$task_data = [
					'code'   => 'country.info.' . $country_id,
					'action' => 'task/catalog/country.info',
					'args'   => ['country_id' => $country_id]
				];

				$this->model_setting_task->addTask($task_data);
			}

			// Update country info for any removed geo zones
			$this->load->model('localisation/geo_zone');

			$results = $this->model_localisation_geo_zone->getZones($args[0]);

			foreach ($results as $result) {
				if (!in_array($result['country_id'], $country_ids)) {
					$task_data = [
						'code'   => 'country.info.' . $result['country_id'],
						'action' => 'task/catalog/country.info',
						'args'   => ['country_id' => $result['country_id']]
					];

					$this->model_setting_task->addTask($task_data);
				}
			}
		}
	}

	/**
	 * Delete Geo Zone
	 *
	 * Generate new tax rate info data by geo zone ID.
	 *
	 * Called using admin/model/localisation/zone.deleteGeoZone/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function deleteGeoZone(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'tax_rate.info.' . $args[0],
			'action' => 'task/admin/country.info',
			'args'   => ['geo_zone_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Update countries based on geo zones.
		$this->load->model('localisation/geo_zone');

		$results = $this->model_localisation_geo_zone->getZones($args[0]);

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'country.info.' . $result['country_id'],
				'action' => 'task/catalog/country.info',
				'args'   => ['country_id' => $result['country_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}