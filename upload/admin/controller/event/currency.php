<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Currency
 *
 * @package Opencart\Admin\Controller\Event
 */
class Currency extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Auto update currencies
	 *
	 * Called using model/localisation/currency/addCurrency/after
	 * Called using model/localisation/currency/editCurrency/after
	 * Called using model/localisation/currency/deleteCurrency/after
	 * Called using model/setting/setting/editSetting/after
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
			'action' => 'catalog/currency',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'currency',
			'action' => 'admin/currency',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);
	}
}
