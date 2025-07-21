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
	public function index(string &$route, array &$args): void {
		$tasks = [];

		$tasks[] = [
			'code'   => 'translation',
			'action' => 'catalog/cli/data/translation',
			'args'   => []
		];

		$tasks[] = [
			'code'   => 'translation',
			'action' => 'admin/cli/data/translation',
			'args'   => []
		];

		$this->load->model('setting/task');

		foreach ($tasks as $task) {
			$this->model_setting_task->addTask($task);
		}
	}
}
