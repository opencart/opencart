<?php
namespace Opencart\Admin\Controller\Cron;
/**
 * Class Backup
 *
 * @package Opencart\Catalog\Controller\Cron
 */
class Backup extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @param int    $cron_id
	 * @param string $code
	 * @param string $cycle
	 * @param string $date_added
	 * @param string $date_modified
	 *
	 * @return void
	 */
	public function index(int $cron_id, string $code, string $cycle, string $date_added, string $date_modified): void {
		$task_data = [
			'code'   => 'backup',
			'action' => 'task/system/backup',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
