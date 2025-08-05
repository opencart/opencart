<?php
namespace Opencart\Admin\Controller\Cron;
/**
 * Class Gdpr
 *
 * @package Opencart\Catalog\Controller\Cron
 */
class Gdpr extends \Opencart\System\Engine\Controller {
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
			'code'   => 'gdpr',
			'action' => 'task/admin/gdpr',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
