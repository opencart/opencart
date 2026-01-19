<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Store
 *
 * @package Opencart\Admin\Controller\Event
 */
class Store extends \Opencart\System\Engine\Controller {
	/**
	 * Add Store
	 *
	 * Adds task to generate new store list
	 *
	 * model/setting/store/addStore
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function addStore(string &$route, array &$args, &$output): void {
		// Language
		$task_data = [
			'code'   => 'language',
			'action' => 'task/catalog/language',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Currency
		if ($this->config->get('config_currency_auto')) {
			$task_data = [
				'code'   => 'currency',
				'action' => 'task/catalog/currency',
				'args'   => []
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Location
		$task_data = [
			'code'   => 'location',
			'action' => 'task/catalog/location',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);






		$task_data = [
			'code'   => 'store',
			'action' => 'task/admin/store',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Edit Store
	 *
	 * Adds task to generate new store list
	 *
	 * model/setting/store/editStore
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function editStore(string &$route, array &$args, &$output): void {


		// Language
		$task_data = [
			'code'   => 'language',
			'action' => 'task/catalog/language',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Currency
		if ($this->config->get('config_currency_auto')) {
			$task_data = [
				'code'   => 'currency',
				'action' => 'task/catalog/currency',
				'args'   => []
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Location
		$task_data = [
			'code'   => 'location',
			'action' => 'task/catalog/location',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);





		$task_data = [
			'code'   => 'store',
			'action' => 'task/admin/store',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Delete Store
	 *
	 * Adds task to generate new store list
	 *
	 * model/setting/store/deleteStore
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function deleteStore(string &$route, array &$args, &$output): void {
		// Language
		$task_data = [
			'code'   => 'language',
			'action' => 'task/catalog/language',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Currency
		if ($this->config->get('config_currency_auto')) {
			$task_data = [
				'code'   => 'currency',
				'action' => 'task/catalog/currency',
				'args'   => []
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Location
		$task_data = [
			'code'   => 'location',
			'action' => 'task/catalog/location',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);


		$task_data = [
			'code'   => 'store',
			'action' => 'task/admin/store',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
