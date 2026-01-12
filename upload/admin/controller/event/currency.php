<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Currency
 *
 * @package Opencart\Admin\Controller\Event
 */
class Currency extends \Opencart\System\Engine\Controller {
	/*
	 * Index
	 *
	 * Adds task to generate new currency data.
	 *
	 * Called using
	 *
	 * admin/model/localisation/currency/addCategory/after
	 * admin/model/localisation/currency/editCategory/after
	 * admin/model/localisation/currency/deleteCategory/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'currency.list',
			'action' => 'task/catalog/currency',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		/*
		// Admin
		$task_data = [
			'code'   => 'admin.currency',
			'action' => 'task/admin/currency',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);
		*/
	}
}