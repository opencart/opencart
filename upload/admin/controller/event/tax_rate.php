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
		$task_data = [
			'code'   => 'tax_rate.' . $args[1]['geo_zone_id'],
			'action' => 'task/catalog/tax_rate',
			'args'   => ['geo_zone_id' => $args[1]['geo_zone_id']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
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
		$task_data = [
			'code'   => 'tax_rate.' . $args[1]['geo_zone_id'],
			'action' => 'task/catalog/tax_rate',
			'args'   => ['geo_zone_id' => $args[1]['geo_zone_id']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$this->load->model('localisation/tax_rate');

		$tax_rate_info = $this->model_localisation_tax_rate->getTaxRate($args[0]);

		if ($args[1]['geo_zone_id'] != $tax_rate_info['geo_zone_id']) {
			$task_data = [
				'code'   => 'tax_rate.' . $tax_rate_info['geo_zone_id'],
				'action' => 'task/catalog/tax_rate',
				'args'   => ['geo_zone_id' => $tax_rate_info['geo_zone_id']]
			];

			$this->model_setting_task->addTask($task_data);
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
		$this->load->model('localisation/tax_rate');

		$tax_rate_info = $this->model_localisation_tax_rate->getTaxRate($args[0]);

		if ($tax_rate_info) {
			$task_data = [
				'code'   => 'tax_rate.delete.' . $tax_rate_info['geo_zone_id'],
				'action' => 'task/admin/tax_rate.delete',
				'args'   => ['geo_zone_id' => $tax_rate_info['geo_zone_id']]
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);
		}
	}
}