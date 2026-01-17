<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Zone
 *
 * @package Opencart\Admin\Controller\Event
 */
class Zone extends \Opencart\System\Engine\Controller {
	/**
	 * Add Zone
	 *
	 * Generate new country data with added zone.
	 *
	 * Called using admin/model/localisation/zone.addZone/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function addZone(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'country.info.' . $args[1]['country_id'],
			'action' => 'task/catalog/country.info',
			'args'   => ['country_id' => $args[1]['country_id']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		/*
		// Admin
		$task_data = [
			'code'   => 'country',
			'action' => 'task/admin/country.list',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'country',
			'action' => 'task/admin/country.info',
			'args'   => ['country_id' => $output]
		];

		$this->model_setting_task->addTask($task_data);
		*/
	}

	/**
	 * Edit Zone
	 *
	 * Generate new country data with updated zone.
	 *
	 * Called using admin/model/localisation/zone.editZone/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function editZone(string &$route, array &$args, &$output): void {
		$zone_info = $this->model_localisation_zone->getZone($args[0]);

		if ($zone_info) {
			$task_data = [
				'code'   => 'country.info.' . $args[1]['country_id'],
				'action' => 'task/catalog/country.info',
				'args'   => ['country_id' => $args[1]['country_id']]
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);

			// In case country was switched we want to update old country
			if ($args[1]['country_id'] != $zone_info['country_id']) {
				$task_data = [
					'code'   => 'country.info.' . $zone_info['country_id'],
					'action' => 'task/catalog/country.info',
					'args'   => ['country_id' => $zone_info['country_id']]
				];

				$this->load->model('setting/task');

				$this->model_setting_task->addTask($task_data);
			}
		}

		/*
		// Admin
		$task_data = [
			'code'   => 'country',
			'action' => 'task/admin/country.list',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'country',
			'action' => 'task/admin/country.info',
			'args'   => ['country_id' => $args[0]]
		];

		$this->model_setting_task->addTask($task_data);
		*/
	}

	/**
	 * Delete Zone
	 *
	 * Generate new country data with deleted zone.
	 *
	 * Called using admin/model/localisation/zone.deleteZone/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function deleteZone(string &$route, array &$args, &$output): void {
		$this->load->model('localisation/zone');

		$zone_info = $this->model_localisation_zone->getZone($args[0]);

		if ($zone_info) {
			$task_data = [
				'code'   => 'country.info.' . $zone_info['country_id'],
				'action' => 'task/catalog/country.info',
				'args'   => ['country_id' => $zone_info['country_id']]
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);
		}
	}
}