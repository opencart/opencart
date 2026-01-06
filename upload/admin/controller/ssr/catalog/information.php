<?php
namespace Opencart\admin\controller\ssr\catalog;
/**
 * Class Information
 *
 * @package Opencart\Admin\Controller\Event
 */
class Information extends \Opencart\System\Engine\Controller {
	public function addInformation(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'information.add.' . $output,
			'action' => 'task/catalog/information.info',
			'args'   => ['information_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function edit(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'information.edit.' . $args[0],
			'action' => 'task/catalog/information.info',
			'args'   => ['information_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function delete(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'information.delete.' . $args[0],
			'action' => 'task/catalog/information.delete',
			'args'   => ['information_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
