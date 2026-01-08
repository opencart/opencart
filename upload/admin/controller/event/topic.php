<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Topic
 *
 * @package Opencart\Admin\Controller\Event
 */
class Topic extends \Opencart\System\Engine\Controller {
	public function addTopic(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'topic.info.' . $output,
			'action' => 'task/catalog/topic.info',
			'args'   => ['article_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function editTopic(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'topic.info.' . $args[0],
			'action' => 'task/catalog/topic.info',
			'args'   => ['article_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function deleteTopic(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'topic.delete.' . $args[0],
			'action' => 'task/catalog/topic.delete',
			'args'   => ['article_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
