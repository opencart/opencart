<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Setting
 *
 * @package Opencart\Admin\Controller\Event
 */
class Setting extends \Opencart\System\Engine\Controller {
	/**
	 * Update data related to settings.
	 *
	 * Called using model/setting/setting/editSetting/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		if ($route != 'setting/setting.editSetting') {
			// Location
			$task_data = [
				'code'   => 'location',
				'action' => 'task/catalog/location',
				'args'   => []
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);

			// Language
			$task_data = [
				'code'   => 'language',
				'action' => 'task/catalog/language',
				'args'   => []
			];

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

			// Country
			$task_data = [
				'code'   => 'country.list',
				'action' => 'task/catalog/country.list',
				'args'   => []
			];

			$this->model_setting_task->addTask($task_data);

			// Customer Group
			$task_data = [
				'code'   => 'customer_group.list',
				'action' => 'task/catalog/customer_group.list',
				'args'   => []
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}
