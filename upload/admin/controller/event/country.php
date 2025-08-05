<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Country
 *
 * @package Opencart\Admin\Controller\Event
 */
class Country extends \Opencart\System\Engine\Controller {
	/**
	 * Index
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
		$task_data = [
			'code'   => 'country',
			'action' => 'task/catalog/country',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'country',
			'action' => 'task/admin/country',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);
	}
}
