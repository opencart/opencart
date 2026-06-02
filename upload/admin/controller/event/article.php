<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Article
 *
 * @package Opencart\Admin\Controller\Event
 */
class Article extends \Opencart\System\Engine\Controller {
	/**
	 * Add Article
	 *
	 * Adds task to generate new article data.
	 *
	 * Trigger admin/model/cms/article/addArticle/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	private function addArticle(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'article.' . $output,
			'action' => 'task/catalog/article',
			'args'   => ['article_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Topic
		if ($args[1]['topic_id']) {
			$task_data = [
				'code'   => 'article_topic.' . $args[1]['topic_id'],
				'action' => 'task/catalog/article_topic',
				'args'   => ['topic_id' => $args[1]['topic_id']]
			];
		}

		$this->model_setting_task->addTask($task_data);
	}

	/*
	 * Edit Article
	 *
	 * Adds task to generate new article data.
	 *
	 * Trigger admin/model/cms/article/addArticle/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	private function editArticle(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'article.' . $args[0],
			'action' => 'task/catalog/article',
			'args'   => ['article_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Topic
		$this->load->model('cms/article');

		$article_info = $this->model_cms_article->getArticle($args[0]);

		if ($article_info) {
			$topic_ids = array_filter(array_unique([$args[1]['topic_id'], $article_info['topic_id']]));

			foreach ($topic_ids as $topic_id) {
				$task_data = [
					'code'   => 'article_topic.' . $topic_id,
					'action' => 'task/catalog/article_topic',
					'args'   => ['topic_id' => $topic_id]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}



	}

	/*
	 * Delete Article
	 *
	 * Adds task to generate new article data.
	 *
	 * Trigger admin/model/cms/article/deleteArticle/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	private function deleteArticle(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'article.delete.' . $args[0],
			'action' => 'task/catalog/article.delete',
			'args'   => ['article_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Topic
		$this->load->model('cms/article');

		$article_info = $this->model_cms_article->getArticle($args[0]);

		if ($article_info) {
			$task_data = [
				'code'   => 'article_topic.' . $article_info['topic_id'],
				'action' => 'task/catalog/article_topic',
				'args'   => ['topic_id' => $article_info['topic_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}
