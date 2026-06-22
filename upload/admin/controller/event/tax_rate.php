<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Tax Rate
 *
 * @package Opencart\Admin\Controller\Event
 */
class TaxRate extends \Opencart\System\Engine\Controller {
	/**
	 * Add Tax Rate
	 *
	 * Generate new tax rate data with geo zone ID.
	 *
	 * Trigger admin/model/localisation/geo_zone/addTaxRate/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function addTaxRate(string &$route, array &$args, &$output): void {
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'tax_rate.info.' . $store_id . '.' . $args[1]['geo_zone_id'],
				'action' => 'task/catalog/tax_rate.info',
				'args'   => [
					'geo_zone_id' => $args[1]['geo_zone_id'],
					'store_id'    => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/**
	 * Edit Tax Rate
	 *
	 * Generate new tax rate data with updated geo zone ID.
	 *
	 * Trigger admin/model/localisation/tax_rate/editTaxRate/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function editTaxRate(string &$route, array &$args): void {
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$this->load->model('localisation/tax_rate');

		$tax_rate_info = $this->model_localisation_tax_rate->getTaxRate($args[0]);

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'tax_rate.info.' . $store_id . '.' . $args[1]['geo_zone_id'],
				'action' => 'task/catalog/tax_rate.info',
				'args'   => [
					'geo_zone_id' => $args[1]['geo_zone_id'],
					'store_id'    => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);

			if ($tax_rate_info && $tax_rate_info['geo_zone_id'] !== $args[1]['geo_zone_id']) {
				$task_data = [
					'code'   => 'tax_rate.info.' . $store_id . '.' . $tax_rate_info['geo_zone_id'],
					'action' => 'task/catalog/tax_rate.info',
					'args'   => [
						'geo_zone_id' => $tax_rate_info['geo_zone_id'],
						'store_id'    => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}
	}

	/**
	 * Delete Tax Rate
	 *
	 * Generate new country info page with deleted zone.
	 *
	 * Trigger admin/model/localisation/zone/deleteZone/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function deleteTaxRate(string &$route, array &$args): void {
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$this->load->model('localisation/tax_rate');

		$tax_rate_info = $this->model_localisation_tax_rate->getTaxRate($args[0]);

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			if ($tax_rate_info) {
				$task_data = [
					'code'   => 'tax_rate.info.' .  $store_id . '.' . $tax_rate_info['geo_zone_id'],
					'action' => 'task/catalog/tax_rate.info',
					'args'   => [
						'geo_zone_id' => $tax_rate_info['geo_zone_id'],
						'store_id'    => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}
	}
}