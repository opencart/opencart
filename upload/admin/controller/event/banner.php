<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Banner
 *
 * @package Opencart\Admin\Controller\Event
 */
class Banner extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new banner list
	 *
	 * Called using admin/model/design/banner/addBanner/after
	 * Called using admin/model/design/banner/editBanner/after
	 * Called using admin/model/design/banner/deleteBanner/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'banner',
			'action' => 'task/catalog/banner',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
