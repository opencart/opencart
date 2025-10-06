
<?php
namespace Opencart\Catalog\Controller\Startup;
/**
 * Class Task
 *
 * @package Opencart\Admin\Controller\Startup
 */
class Task extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Starts task queue if not running
	 *
	 * @return void
	 */
	public function index(): void {
		register_shutdown_function([$this, 'trigger']);
	}

	public function trigger(): void {
		$this->load->model('setting/task');

		$task_total = $this->model_setting_task->getTotalTasks(['filter_status' => 'processing']);

		if ($task_total) {
			return;
		}

		pclose(popen(DIR_APPLICATION . 'index.php start > ' . DIR_LOGS . 'test.log 2>&1', 'r'));

		//exec('php ' . DIR_APPLICATION . 'index.php start 2>&1');
	}
}