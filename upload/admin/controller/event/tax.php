<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Tax Class
 *
 * @package Opencart\Admin\Controller\Event
 */
class Tax extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new tax_class list
	 *
	 * model/localisation/tax_class/addTaxClass/after
	 * model/localisation/tax_class/editTaxClass/after
	 * model/localisation/tax_class/deleteTaxClass/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		// Tax Class
		//$task_data = [
		//	'code'   => 'tax',
		//	'action' => 'task/admin/tax_class',
		//	'args'   => []
		//];

		$this->load->model('setting/task');

		//$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'tax',
			'action' => 'task/catalog/tax_class',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		// Tax Rate
		//$task_data = [
		//	'code'   => 'tax',
		//	'action' => 'task/admin/tax_rate',
		//	'args'   => []
		//];

		//$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'tax',
			'action' => 'task/catalog/tax_rate',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);
	}
}
