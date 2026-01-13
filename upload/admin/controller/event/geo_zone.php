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
	 * Generate new tax rate info page with added geo zone data.
	 *
	 * Called using admin/model/localisation/geo_zone/addGeoZone
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function addGeoZone(string &$route, array &$args, &$output): void {

		getZonesByCountryId

		$args[1]['zone_to_geo_zone'];

		foreach (zone_to_geo_zone as zone_to_geo_zone) {


		}

		// Update tax rates based on geo zone
		$task_data = [
			'code'   => 'tax_rate.info.' . $output,
			'action' => 'task/catalog/tax_rate.info',
			'args'   => ['geo_zone_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Edit Geo Zone
	 *
	 * Generate new tax rate info page with updated geo zone data.
	 *
	 * Called using admin/model/localisation/zone/editGeoZone
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
	}

	/**
	 * Delete Geo Zone
	 *
	 * Generate new country info page with deleted zone.
	 *
	 * Called using admin/model/localisation/zone/deleteGeoZone
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
	}
}