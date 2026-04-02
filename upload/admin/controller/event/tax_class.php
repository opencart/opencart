<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Tax Class
 *
 * @package Opencart\Admin\Controller\Event
 */
class TaxClass extends \Opencart\System\Engine\Controller {
	/**
	 * Add Tax Class
	 *
	 * Generate new tax rate data with geo zone ID.
	 *
	 * Trigger admin/model/localisation/tax_class/addTaxClass/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function addTaxClass(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'tax_class.' . $output,
			'action' => 'task/catalog/tax_class',
			'args'   => ['tax_class_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$this->load->model('localisation/tax_class');
		$this->load->model('localisation/tax_rate');

		$results = $this->model_localisation_tax_class->getTaxRules($args[0]);

		foreach ($results as $result) {
			$tax_rule_info = $this->model_localisation_tax_rate->getTaxRate($result['tax_rate_id']);

			if ($tax_rule_info) {
				$task_data = [
					'code'   => 'tax_rate.' . $tax_rule_info['geo_zone_id'],
					'action' => 'task/catalog/tax_rate',
					'args'   => ['geo_zone_id' => $tax_rule_info['geo_zone_id']]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}
	}

	/**
	 * Edit Tax Rate
	 *
	 * Generate new tax rate data with updated geo zone ID.
	 *
	 * Trigger admin/model/localisation/tax_class/editTaxClass/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function editTaxClass(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'tax_class.' . $args[0],
			'action' => 'task/catalog/tax_class',
			'args'   => ['tax_class_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$this->load->model('localisation/tax_class');
		$this->load->model('localisation/tax_rate');
		
		$results = $this->model_localisation_tax_class->getTaxRules($args[0]);

		foreach ($results as $result) {
			$tax_rule_info = $this->model_localisation_tax_rate->getTaxRule($result['tax_rule_id']);

			if ($tax_rule_info) {
				$task_data = [
					'code'   => 'tax_rate.' . $tax_rule_info['geo_zone_id'],
					'action' => 'task/catalog/tax_rate',
					'args'   => ['geo_zone_id' => $tax_rule_info['geo_zone_id']]
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
	 * Trigger admin/model/localisation/tax_class/deleteTaxClass/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function deleteTaxClass(string &$route, array &$args): void {
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
