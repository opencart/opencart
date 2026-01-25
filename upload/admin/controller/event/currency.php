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
	 * Triggered using admin/model/localisation/currency/addCategory/after
	 * Triggered using admin/model/localisation/currency/editCategory/after
	 * Triggered using admin/model/localisation/currency/deleteCategory/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'currency',
			'action' => 'task/catalog/currency',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}