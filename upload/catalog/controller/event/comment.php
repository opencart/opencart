<?php
namespace Opencart\Catalog\Controller\Event;
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
	 * Trigger catalog/model/catalog/comment/addComment/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function addComment(string &$route, array &$args, &$output): void {
		if ($this->config->get('config_comment_approve')) {
			$task_data = [
				'code'   => 'comment.' . $args[1]['article_id'],
				'action' => 'task/catalog/comment',
				'args'   => ['article_id' => $args[1]['article_id']]
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);
		}
	}
}
