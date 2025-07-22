<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Topic
 *
 * @package Opencart\Admin\Controller\Event
 */
class Topic extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new topic list
	 *
	 * Called using admin/model/cms/topic/addTopic/after
	 * Called using admin/model/cms/topic/editTopic/after
	 * Called using admin/model/cms/topic/deleteTopic/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args): void {
		$tasks = [];

		$tasks[] = [
			'code'   => 'article',
			'action' => 'catalog/cli/data/article',
			'args'   => []
		];

		$this->load->model('setting/task');

		foreach ($tasks as $task) {
			$this->model_setting_task->addTask($task);
		}
	}
}
