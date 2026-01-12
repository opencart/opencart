<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Country
 *
 * @package Opencart\Admin\Controller\Event
 */
class Country extends \Opencart\System\Engine\Controller {
	/**
	 * Add Country
	 *
	 * Adds task to generate new country data.
	 *
	 * Called using admin/model/localisation/country/addCountry/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function addCountry(string &$route, array &$args, &$output): void {
		// Generate new country list.
		$task_data = [
			'code'   => 'country.list',
			'action' => 'task/catalog/country.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Generate new country info page.
		$task_data = [
			'code'   => 'country.info.' . $output,
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

	/**
	 * Edit Country
	 *
	 * Adds task to generate new country data.
	 *
	 * Called using admin/model/localisation/country/editCountry/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function editCountry(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'country.list',
			'action' => 'task/catalog/country.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'country.info.' . $args[0],
			'action' => 'task/catalog/country.info',
			'args'   => ['country_id' => $args[0]]
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
			'args'   => ['country_id' => $args[0]]
		];

		$this->model_setting_task->addTask($task_data);
		*/
	}

	/**
	 * Delete Country
	 *
	 * Adds task to generate new country data.
	 *
	 * Called using admin/model/localisation/country/deleteCountry/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function deleteCountry(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'country.list',
			'action' => 'task/catalog/country.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'country.delete.' . $args[0],
			'action' => 'task/catalog/country.delete',
			'args'   => ['country_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		/*
		$task_data = [
			'code'   => 'country.delete.' . $args[0],
			'action' => 'task/admin/country.delete',
			'args'   => ['country_id' => $args[0]]
		];

		$this->model_setting_task->addTask($task_data);
		*/
	}
}
