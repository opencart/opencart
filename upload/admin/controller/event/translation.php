<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Translation
 *
 * @package Opencart\Admin\Controller\Event
 */
class Translation extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new translation list
	 *
	 * Called using admin/model/localisation/country/addTranslation/after
	 * Called using admin/model/localisation/country/editTranslation/after
	 * Called using admin/model/localisation/country/deleteTranslation/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'translation',
			'action' => 'task/catalog/translation',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'translation',
			'action' => 'task/admin/translation',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);
	}
}
