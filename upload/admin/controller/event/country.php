<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Country
 *
 * @package Opencart\Admin\Controller\Event
 */
class Country extends \Opencart\System\Engine\Controller {
	/**
	 * Add
	 *
	 * Adds task to generate new country list
	 *
	 * Called using admin/model/localisation/country/addCountry/after
	 * Called using admin/model/localisation/country/editCountry/after
	 * Called using admin/model/localisation/country/deleteCountry/after
	 *
	 * Called using admin/model/localisation/zone/addZone
	 * Called using admin/model/localisation/zone/editZone
	 * Called using admin/model/localisation/zone/deleteZone
     *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function add(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'country.add.' . $output,
			'action' => 'task/catalog/country.info',
			'args'   => ['country_id' => $output]
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

	public function edit(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'country.edit.' . $args[0],
			'action' => 'task/catalog/country.info',
			'args'   => ['country_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Admin
		/*
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

	public function delete(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'country.delete.' . $args[0],
			'action' => 'task/admin/country.delete',
			'args'   => ['country_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
