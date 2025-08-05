<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Stock Status
 *
 * @package Opencart\Admin\Controller\Event
 */
class StockStatus extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new stock status list
	 *
	 * model/localisation/stock_status/addStockStatus/after
	 * model/localisation/stock_status/editStockStatus/after
	 * model/localisation/stock_status/deleteStockStatus/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'stock_status',
			'action' => 'task/admin/stock_status',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
