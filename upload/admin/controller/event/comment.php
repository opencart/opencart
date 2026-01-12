<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Comment
 *
 * @package Opencart\Admin\Controller\Event
 */
class Comment extends \Opencart\System\Engine\Controller {
	public function addComment(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'comment.list.' . $args['article_id'],
			'action' => 'task/catalog/comment',
			'args'   => ['article_id' => $args['article_id']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function editComment(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'comment.list.' . $args['article_id'],
			'action' => 'task/catalog/comment',
			'args'   => ['article_id' => $args['article_id']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function deleteComment(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'comment.list.' . $args['article_id'],
			'action' => 'task/catalog/comment',
			'args'   => ['article_id' => $args['article_id']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
