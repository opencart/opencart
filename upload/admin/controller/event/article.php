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
	public function addArticle(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');

		$store_ids = [];

		if (isset($args[1]['article_store'])) {
			$store_ids = (array)$args[1]['article_store'];
		}

		// Tags
		$tag_ids = [];

		if (isset($args[1]['product_tag'])) {
			$tag_ids = (array)$args[1]['product_tag'];
		}

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'article.info.' . $store_id . '.' . $output,
				'action' => 'task/catalog/article.info',
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

			// Tags
			foreach ($tag_ids as $tag_id) {
				$task_data = [
					'code'   => 'tag.product.' . $store_id . '.' . $tag_id,
					'action' => 'task/catalog/tag.product',
					'args'   => [
						'tag_id'   => $tag_id,
						'store_id' => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
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
	public function editArticle(string &$route, array &$args): void {
		$this->load->model('setting/task');

		// Rewrite the article ID's in the topic file
		$this->load->model('cms/article');

		$article_info = $this->model_cms_article->getArticle($args[0]);

		$store_ids = [];

		if (isset($args[1]['article_store'])) {
			$store_ids = (array)$args[1]['article_store'];
		}

		// Tags
		$tag_ids = [];

		if (isset($args[1]['article_tag'])) {
			$tag_ids = (array)$args[1]['article_tag'];
		}

		$tag_ids = array_unique(array_merge($this->model_cms_article->getTags($args[0]), $tag_ids));

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'article.info.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/article',
				'args'   => [
					'article_id' => $args[0],
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

			if ($article_info && $article_info['topic_id'] !== $args[1]['topic_id']) {
				$task_data = [
					'code'   => 'topic.article.' . $store_id . '.' . $article_info['topic_id'],
					'action' => 'task/catalog/topic.article',
					'args'   => [
						'topic_id' => $article_info['topic_id'],
						'store_id' => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}

			// Tags
			foreach ($tag_ids as $tag_id) {
				$task_data = [
					'code'   => 'tag.article.' . $store_id . '.' . $tag_id,
					'action' => 'task/catalog/tag.article',
					'args'   => [
						'tag_id'   => $tag_id,
						'store_id' => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		// Remove from stores
		$remove_ids = array_diff($this->model_cms_article->getStores($args[0]), $store_ids);

		foreach ($remove_ids as $remove_id) {
			$task_data = [
				'code'   => 'article.delete.' . $remove_id . '.' . $args[0],
				'action' => 'task/catalog/article.delete',
				'args'   => [
					'article_id' => $args[0],
					'store_id'   => $remove_id
				]
			];

			// Rewrite the article ID's in the topic file
			if ($article_info) {
				$task_data = [
					'code'   => 'topic.article.' . $remove_id . '.' . $article_info['topic_id'],
					'action' => 'task/catalog/topic.article',
					'args'   => [
						'topic_id' => $article_info['topic_id'],
						'store_id' => $remove_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}

			// Tags
			foreach ($tag_ids as $tag_id) {
				$task_data = [
					'code'   => 'tag.article.' . $remove_id . '.' . $tag_id,
					'action' => 'task/catalog/tag.article',
					'args'   => [
						'tag_id'   => $tag_id,
						'store_id' => $remove_id
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
	public function deleteArticle(string &$route, array &$args): void {
		$this->load->model('setting/task');

		$this->load->model('cms/article');

		$article_info = $this->model_cms_article->getArticle($args[0]);

		// Stores
		$store_ids = $this->model_cms_article->getStores($args[0]);

		// Tags
		$tag_ids = $this->model_cms_article->getTags($args[0]);

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'article.delete.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/article.delete',
				'args'   => [
					'article_id' => $args[0],
					'store_id'   => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);

			if ($article_info) {
				$task_data = [
					'code'   => 'topic.article.' . $store_id . '.' . $article_info['topic_id'],
					'action' => 'task/catalog/topic.article',
					'args'   => [
						'topic_id' => $article_info['topic_id'],
						'store_id' => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}

			// Tags
			foreach ($tag_ids as $tag_id) {
				$task_data = [
					'code'   => 'tag.article.' . $store_id . '.' . $tag_id,
					'action' => 'task/catalog/tag.article',
					'args'   => [
						'tag_id'   => $tag_id,
						'store_id' => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}
	}
}
