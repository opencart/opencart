<?php
namespace Opencart\Admin\Controller\Cron;
/**
 * Class Notification
 *
 * @package Opencart\Admin\Controller\Startup
 */
class Notification extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$task_data = [
			'code'   => 'currency',
			'action' => 'task/system/notification',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
