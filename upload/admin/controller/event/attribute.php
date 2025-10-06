<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Attribute
 *
 * @package Opencart\Admin\Controller\Event
 */
class Attribute extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new filter list
	 *
	 * Called using admin/model/catalog/attribute/addAttribute/after
	 * Called using admin/model/catalog/attribute/editAttribute/after
	 * Called using admin/model/catalog/attribute/deleteAttribute/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'attribute',
			'action' => 'task/catalog/attribute',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
