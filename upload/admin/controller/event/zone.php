<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Zone
 *
 * @package Opencart\Admin\Controller\Event
 */
class Zone extends \Opencart\System\Engine\Controller {
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
	public function index(string &$route, array &$args, &$output): void {
		$pos = strpos($route, '.');

		if ($pos == false) {
			return;
		}

		$method = substr($route, 0, $pos);

		$callable = [$this, $method];

		if (is_callable($callable)) {
			$callable($route, $args, $output);
		}
	}

	public function addZone(string &$route, array &$args, &$output): void {
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

	public function editZone(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'country.info.' . $args[0],
			'action' => 'task/catalog/country.info',
			'args'   => ['country_id' => $args[1]['country_id']]
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

	public function deleteZone(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'country.delete.' . $args[0],
			'action' => 'task/admin/country.delete',
			'args'   => ['country_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}

