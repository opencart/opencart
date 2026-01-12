<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Topic
 *
 * @package Opencart\Admin\Controller\Event
 */
class Topic extends \Opencart\System\Engine\Controller {
	/*
	 * Add Topic
	 *
	 * Adds task to generate new topic data.
	 *
	 * Called using admin/model/cms/topic/addTopic/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function addTopic(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'topic.info.' . $output,
			'action' => 'task/catalog/topic.info',
			'args'   => ['topic_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/*
	 * Edit Topic
	 *
	 * Adds task to generate new topic data.
	 *
	 * Called using admin/model/cms/topic/editTopic/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function editTopic(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'topic.info.' . $args[0],
			'action' => 'task/catalog/topic.info',
			'args'   => ['topic_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/*
	 * Delete Topic
	 *
	 * Adds task to generate delete topic data.
	 *
	 * Called using admin/model/cms/topic/deleteTopic/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function deleteTopic(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'topic.delete.' . $args[0],
			'action' => 'task/catalog/topic.delete',
			'args'   => ['topic_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
