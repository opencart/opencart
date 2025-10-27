<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Information
 *
 * @package Opencart\Admin\Controller\Event
 */
class Information extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new information list
	 *
	 * Called using admin/model/customer/information/addInformation/after
	 * Called using admin/model/customer/information/editInformation/after
	 * Called using admin/model/customer/information/deleteInformation/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'information',
			'action' => 'task/admin/information',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$files = oc_directory_read(DIR_OPENCART . 'view/html/');

		foreach ($files as $file) {
			oc_directory_delete($file);
		}
	}
}
