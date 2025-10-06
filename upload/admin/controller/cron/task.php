<?php
namespace Opencart\Admin\Controller\Cron;
/**
 * Class Task
 *
 * @package Opencart\Catalog\Controller\Cron
 */
class Task extends \Opencart\System\Engine\Controller {
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
		$this->load->model('setting/task');

		$task_total = $this->model_setting_task->getTotalTasks(['filter_status' => 'processing']);

		if (!$task_total) {
			if (substr(strtoupper(php_uname()), 0, 3) == 'WIN') {
				pclose(popen('start /B php ' . DIR_APPLICATION . 'index.php start', 'r'));
			} else {
				exec(DIR_APPLICATION . 'index.php start > /dev/null 2>&1 &');
			}
		}
	}
}
