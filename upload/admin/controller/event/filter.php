<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Filter
 *
 * @package Opencart\Admin\Controller\Event
 */
class Filter extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new filter list
	 *
	 * Called using admin/model/catalog/filter/addFilter/after
	 * Called using admin/model/catalog/filter/editFilter/after
	 * Called using admin/model/catalog/filter/deleteFilter/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'filter',
			'action' => 'task/catalog/filter',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
