<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Information
 *
 * @package Opencart\Admin\Controller\Event
 */
class Information extends \Opencart\System\Engine\Controller {
	public function addInformation(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'information.info.' . $output,
			'action' => 'task/catalog/information.info',
			'args'   => ['information_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function editInformation(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'information.info.' . $args[0],
			'action' => 'task/catalog/information.info',
			'args'   => ['information_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function deleteInformation(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'information.delete.' . $args[0],
			'action' => 'task/catalog/information.delete',
			'args'   => ['information_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
