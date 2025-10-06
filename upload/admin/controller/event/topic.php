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
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'article',
			'action' => 'task/catalog/article',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
