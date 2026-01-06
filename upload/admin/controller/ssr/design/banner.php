<?php
namespace Opencart\admin\controller\ssr\design;
/**
 * Class Banner
 *
 * @package Opencart\Admin\Controller\Event
 */
class Banner extends \Opencart\System\Engine\Controller {
	public function add(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'banner.add.' . $output,
			'action' => 'task/catalog/banner',
			'args'   => ['banner_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function edit(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'banner.edit.' . $args[0],
			'action' => 'task/catalog/banner.info',
			'args'   => ['banner_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function delete(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'banner.delete.' . $args[0],
			'action' => 'task/catalog/banner.delete',
			'args'   => ['banner_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
