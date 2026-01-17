<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Location
 *
 * @package Opencart\Admin\Controller\Event
 */
class Location extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new location list.
	 *
	 * model/localisation/location/addLocation/after
	 * model/localisation/location/editLocation/after
	 * model/localisation/location/deleteLocation/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'location',
			'action' => 'task/catalog/location',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
