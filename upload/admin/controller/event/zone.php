<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Country
 *
 * @package Opencart\Admin\Controller\Event
 */
class Zone extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new zone list
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
	public function index(string &$route, array &$args): void {
		$tasks = [];

		$tasks[] = [
			'code'   => 'country',
			'action' => 'catalog/country',
			'args'   => []
		];

		$tasks[] = [
			'code'   => 'country',
			'action' => 'admin/country',
			'args'   => []
		];

		$this->load->model('setting/task');

		foreach ($tasks as $task) {
			$this->model_setting_task->addTask($task);
		}
	}
}
