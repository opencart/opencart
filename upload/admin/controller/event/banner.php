<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Banner
 *
 * @package Opencart\Admin\Controller\Event
 */
class Banner extends \Opencart\System\Engine\Controller {
	public function addBanner(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'banner.info.' . $output,
			'action' => 'task/catalog/banner',
			'args'   => ['banner_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function editBanner(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'banner.info.' . $args[0],
			'action' => 'task/catalog/banner.info',
			'args'   => ['banner_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function deleteBanner(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'banner.delete.' . $args[0],
			'action' => 'task/catalog/banner.delete',
			'args'   => ['banner_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
