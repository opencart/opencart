<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Article
 *
 * Generates banner information for all stores.
 *
 * @package Opencart\Admin\Controller\Event
 */
class Article extends \Opencart\System\Engine\Controller {
	/**
	 * Add Article
	 *
	 * Adds task to generate new article data.
	 *
	 * Trigger admin/model/cms/article.addArticle/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	private function addArticle(string &$route, array &$args, &$output): void {
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'article.' . $store_id . '.' . $output,
				'action' => 'task/catalog/article',
				'args'   => [
					'article_id' => $output,
					'store_id'   => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);

			// Topic
			$task_data = [
				'code'   => 'topic.article.' . $store_id . '.' . $args[1]['topic_id'],
				'action' => 'task/catalog/topic.article',
				'args'   => [
					'topic_id' => $args[1]['topic_id'],
					'store_id' => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/*
	 * Edit Article
	 *
	 * Adds task to generate new article data.
	 *
	 * Trigger admin/model/cms/article.editArticle/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	private function editArticle(string &$route, array &$args, &$output): void {
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$this->load->model('cms/article');

		$article_info = $this->model_cms_article->getArticle($args[0]);

		if ($article_info) {
			$topic_ids = array_filter(array_unique([$args[1]['topic_id'], $article_info['topic_id']]));
		}

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'article.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/article',
				'args'   => [
					'article_id' => $args[0],
				    'store_id'   => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);

			// Topic

			foreach ($topic_ids as $topic_id) {
				$task_data = [
					'code'   => 'topic.article.' . $store_id . '.' . $topic_id,
					'action' => 'task/catalog/topic.article',
					'args'   => [
						'topic_id' => $topic_id,
					    'store_id' => $store_id
					]
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
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];


		// Topic
		$this->load->model('cms/article');

		$article_info = $this->model_cms_article->getArticle($args[0]);

		if ($article_info) {



		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'article.delete.' . $args[0],
				'action' => 'task/catalog/article.delete',
				'args'   => ['article_id' => $args[0]]
			];

			$this->model_setting_task->addTask($task_data);

				$task_data = [
					'code'   => 'topic.article.' . $article_info['topic_id'],
					'action' => 'task/catalog/topic.article_',
					'args'   => ['topic_id' => $article_info['topic_id'],
					             'store_id' => $store_id]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}
	}
}
