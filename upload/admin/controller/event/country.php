<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Country
 *
 * @package Opencart\Admin\Controller\Event
 */
class Country extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new country list
	 *
	 * Called using admin/model/localisation/country/addCountry/after
	 * Called using admin/model/localisation/country/editCountry/after
	 * Called using admin/model/localisation/country/deleteCountry/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args): void {
		$tasks = [];

		$tasks[] = [
			'code'   => 'country',
			'action' => 'catalog/cli/data/country',
			'args'   => []
		];

		$tasks[] = [
			'code'   => 'country',
			'action' => 'admin/cli/data/country',
			'args'   => []
		];

		$tasks[] = [
			'code'   => 'zone',
			'action' => 'catalog/cli/data/zone',
			'args'   => []
		];

		$tasks[] = [
			'code'   => 'zone',
			'action' => 'admin/cli/data/zone',
			'args'   => []
		];

		$this->load->model('setting/task');

		foreach ($tasks as $task) {
			$this->model_setting_task->addTask($task);
		}
	}
}
