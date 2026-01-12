<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Comment
 *
 * @package Opencart\Admin\Controller\Event
 */
class Comment extends \Opencart\System\Engine\Controller {
	/*
	 * Add Comment
	 *
	 * Adds task to generate new comment data.
	 *
	 * Called using admin/model/cms/comment/addComment/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function addComment(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'comment.list.' . $args['article_id'],
			'action' => 'task/catalog/comment',
			'args'   => ['article_id' => $args['article_id']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/*
	 * Edit Comment
	 *
	 * Adds task to generate new comment data.
	 *
	 * Called using admin/model/cms/comment/editComment/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function editComment(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'comment.list.' . $args['article_id'],
			'action' => 'task/catalog/comment',
			'args'   => ['article_id' => $args['article_id']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/*
	 * Delete Comment
	 *
	 * Adds task to generate new comment data.
	 *
	 * Called using admin/model/cms/comment/deleteComment/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
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
