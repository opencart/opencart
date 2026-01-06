<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Manufacturer
 *
 * @package Opencart\Admin\Controller\Event
 */
class Manufacturer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new manufacturer list
	 *
	 * Called using admin/model/catalog/manufacturer/addManufacturer/after
	 * Called using admin/model/catalog/manufacturer/editManufacturer/after
	 * Called using admin/model/catalog/manufacturer/deleteManufacturer/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function add(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'manufacturer.add.' . $output,
			'action' => 'task/catalog/manufacturer.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function edit(string &$route, array &$args, &$output): void {
		// Catalog
		$task_data = [
			'code'   => 'manufacturer',
			'action' => 'task/catalog/manufacturer.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'customer_group',
			'action' => 'task/catalog/customer_group.info',
			'args'   => ['customer_group_id' => $args[0]]
		];

		$this->model_setting_task->addTask($task_data);
	}


	public function delete(string &$route, array &$args, &$output): void {
		// Catalog
		$task_data = [
			'code'   => 'country',
			'action' => 'task/catalog/country.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'country',
			'action' => 'task/catalog/country.delete',
			'args'   => ['customer_group_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
