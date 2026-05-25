<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Attribute
 *
 * @package Opencart\Admin\Controller\Event
 */
class AddressFormat extends \Opencart\System\Engine\Controller {
	/*
	 * Edit Country
	 *
	 * Adds task to generate new product data.
	 *
	 * Trigger admin/model/catalog/localisation/editAddressFormat/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function editAddressFormat(string &$route, array &$args, &$output): void {
		$this->load->model('localisation/country');

		$results = $this->model_localisation_country->getCountriesByAddressFormatId($args[0]);

		$this->load->model('setting/task');

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'country.' . $result['country_id'],
				'action' => 'task/catalog/country',
				'args'   => ['country_id' => $result['country_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}
