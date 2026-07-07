<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Address Format
 *
 * @package Opencart\Admin\Controller\Event
 */
class AddressFormat extends \Opencart\System\Engine\Controller {
	/*
	 * Edit Address Format
	 *
	 * Adds task to generate new address format data.
	 *
	 * Trigger admin/model/catalog/localisation.editAddressFormat/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function editAddressFormat(string &$route, array &$args): void {
		$this->load->model('setting/task');

		$this->load->model('localisation/country');

		$results = $this->model_localisation_country->getCountriesByAddressFormatId($args[0]);

		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			foreach ($results as $result) {
				$task_data = [
					'code'   => 'country.info.' . $store_id . '.' . $result['country_id'],
					'action' => 'task/catalog/country',
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
