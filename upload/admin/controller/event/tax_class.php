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
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$tax_rate_ids = [];

		// Update countries based on geo zones.
		if (isset($args[1]['tax_rule']) && is_array($args[1]['tax_rule'])) {
			$tax_rate_ids = array_unique(array_column($args[1]['tax_rule'], 'tax_rate_id'));
		}

		$geo_zone_ids = [];

		$this->load->model('localisation/tax_rate');

		foreach ($tax_rate_ids as $tax_rate_id) {
			$tax_rate_info = $this->model_localisation_tax_rate->getTaxRate($tax_rate_id);

			if ($tax_rate_info) {
				$geo_zone_ids[] = $tax_rate_info['geo_zone_id'];
			}
		}

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			foreach ($geo_zone_ids as $geo_zone_id) {
				$task_data = [
					'code'   => 'tax_rate.' . $store_id . '.' . $geo_zone_id,
					'action' => 'task/catalog/tax_rate',
					'args'   => [
						'geo_zone_id' => $geo_zone_id,
						'store_id'    => $store_id
					]
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
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$tax_rate_ids = [];

		// Update countries based on geo zones.
		if (isset($args[1]['tax_rule']) && is_array($args[1]['tax_rule'])) {
			$tax_rate_ids = array_unique(array_column($args[1]['tax_rule'], 'tax_rate_id'));
		}

		$geo_zone_ids = [];

		$this->load->model('localisation/tax_class');
		$this->load->model('localisation/tax_rate');

		$tax_rate_ids = array_unique(array_merge(array_column($this->model_localisation_tax_class->getTaxRules($args[0]), 'tax_rate_id'), $tax_rate_ids));

		foreach ($tax_rate_ids as $tax_rate_id) {
			$tax_rate_info = $this->model_localisation_tax_rate->getTaxRate($tax_rate_id);

			if ($tax_rate_info) {
				$geo_zone_ids[] = $tax_rate_info['geo_zone_id'];
			}
		}

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			foreach ($geo_zone_ids as $geo_zone_id) {
				$task_data = [
					'code'   => 'tax_rate.' . $store_id . '.' . $geo_zone_id,
					'action' => 'task/catalog/tax_rate',
					'args'   => [
						'geo_zone_id' => $geo_zone_id,
						'store_id'    => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}
	}

	/**
	 * Delete Tax Class
	 *
	 * Generate new tax class info page with deleted zone.
	 *
	 * Trigger admin/model/localisation/tax_class/deleteTaxClass/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function deleteTaxClass(string &$route, array &$args): void {
		$this->load->model('setting/task');

		$geo_zone_ids = [];

		$this->load->model('localisation/tax_class');
		$this->load->model('localisation/tax_rate');

		$results = $this->model_localisation_tax_class->getTaxRules($args[0]);

		$tax_rate_ids = array_unique(array_column($results, 'tax_rate_id'));

		foreach ($tax_rate_ids as $tax_rate_id) {
			$tax_rate_info = $this->model_localisation_tax_rate->getTaxRate($tax_rate_id);

			if ($tax_rate_info) {
				$geo_zone_ids[] = $tax_rate_info['geo_zone_id'];
			}
		}

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			foreach ($geo_zone_ids as $geo_zone_id) {
				$task_data = [
					'code'   => 'tax_rate.' . $store_id . '.' . $geo_zone_id,
					'action' => 'task/admin/tax_rate',
					'args'   => [
						'geo_zone_id' => $geo_zone_id,
						'store_id'    => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}
	}
}