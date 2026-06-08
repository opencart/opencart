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
	 * Trigger admin/model/localization/zone.addZone/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function addZone(string &$route, array &$args, &$output): void {
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		// Admin
		$task_data = [
			'code'   => 'admin.country.info.' . $args[1]['country_id'],
			'action' => 'task/admin/country.info',
			'args'   => ['country_id' => $args[1]['country_id']]
		];

		$this->model_setting_task->addTask($task_data);

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'country.info.' . $store_id . '.' . $args[1]['country_id'],
				'action' => 'task/catalog/country.info',
				'args'   => [
					'country_id' => $args[1]['country_id'],
					'store_id'   => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/**
	 * Edit Zone
	 *
	 * Generate new country data with updated zone.
	 *
	 * Trigger admin/model/localisation/zone.editZone/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function editZone(string &$route, array &$args, &$output): void {
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		// Admin
		$task_data = [
			'code'   => 'admin.country.info.' . $args[1]['country_id'],
			'action' => 'task/admin/country.info',
			'args'   => ['country_id' => $args[1]['country_id']]
		];

		$this->model_setting_task->addTask($task_data);

		$this->load->model('localisation/zone');

		$zone_info = $this->model_localisation_zone->getZone($args[0]);

		if ($zone_info && $zone_info['country_id'] !== $args[1]['country_id']) {
			$task_data = [
				'code'   => 'admin.country.info.' . $zone_info['country_id'],
				'action' => 'task/admin/country.info',
				'args'   => ['country_id' => $zone_info['country_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'country.info.' . $store_id . '.' . $args[1]['country_id'],
				'action' => 'task/catalog/country.info',
				'args'   => [
					'country_id' => $args[1]['country_id'],
					'store_id'   => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);

			if ($zone_info && $zone_info['country_id'] !== $args[1]['country_id']) {
				$task_data = [
					'code'   => 'country.country.' . $zone_info['country_id'],
					'action' => 'task/catalog/country.info',
					'args'   => [
						'country_id' => $zone_info['country_id'],
						'store_id'   => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}
	}

	/**
	 * Delete Zone
	 *
	 * Generate new country data with deleted zone.
	 *
	 * Trigger admin/model/localisation/zone.deleteZone/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function deleteZone(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');
		$this->load->model('setting/store');

		$this->load->model('localisation/zone');

		$zone_info = $this->model_localisation_zone->getZone($args[0]);

		if ($zone_info) {
			$task_data = [
				'code'   => 'admin.country.info.' . $zone_info['country_id'],
				'action' => 'task/admin/country.info',
				'args'   => ['country_id' => $zone_info['country_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			if ($zone_info) {
				$task_data = [
					'code'   => 'country.info.' . $store_id . '.' . $zone_info['country_id'],
					'action' => 'task/catalog/country.info',
					'args'   => [
						'country_id' => $zone_info['country_id'],
						'store_id'   => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}
	}
}