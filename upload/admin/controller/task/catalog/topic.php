<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Topic
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Topic extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates topic task list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/topic');

		$this->load->model('setting/task');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$sorts = [];

		$sorts[] = [
			'sort'  => 'date_added',
			'order' => 'ASC'
		];

		$sorts[] = [
			'sort'  => 'date_added',
			'order' => 'DESC'
		];

		$sorts[] = [
			'sort'  => 'rating',
			'order' => 'ASC'
		];

		$sorts[] = [
			'sort'  => 'rating',
			'order' => 'DESC'
		];

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				foreach ($sorts as $sort) {
					$task_data = [
						'code'   => 'topic',
						'action' => 'task/catalog/topic.list',
						'args'   => [
							'store_id'    => $store['store_id'],
							'language_id' => $language['language_id'],
							'sort'        => $sort['sort'],
							'order'       => $sort['order'],
							'limit'       => $this->config->get('config_pagination')
						]
					];

					$this->model_setting_task->addTask($task_data);
				}
			}
		}

		return ['success' => $this->language->get('text_success')];
	}

	public function list(array $args = []): array {
		// Store
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$directory = DIR_CATALOG . 'view/data/cms/';

		$limit = 5;

		// Total Topics
		$topic_total = $this->model_cms_topic->getTotalTopics();

		$start = ($page - 1) * $limit;
		$end = $start > ($topic_total - $limit) ? $topic_total : ($start + $limit);

		$filter_data = [
			'start' => $start,
			'limit' => $limit
		];

		$this->load->model('cms/topic');

		$topics = $this->model_cms_topic->getTopics($filter_data);

		foreach ($topics as $topic) {
			if ($topic['status']) {
				$descriptions = $this->model_cms_topic->getDescriptions($topic['topic_id']);

				foreach ($descriptions as $description) {
					if (isset($languages[$description['language_id']])) {
						$code = preg_replace('/[^A-Z0-9_-]/i', '', $languages[$description['language_id']]['code']);

						$file = DIR_CATALOG . 'view/data/cms/topic.' . (int)$topic['topic_id'] . '.' . $code . '.json';

						if (!file_put_contents($file, json_encode($description + $topic))) {
							$json['error'] = $this->language->get('error_file');
						}
					}
				}
			}
		}



		$json['text'] = sprintf($this->language->get('text_topic'), $start, $end, $topic_total);

		if ($end < $topic_total) {
			$json['next'] = $this->url->link('task/catalog/topic', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
		} else {
			$json['success'] = $this->language->get('text_success');
		}

		if (!is_dir($directory) && !mkdir($directory, 0777)) {
			$json['error'] = $this->language->get('error_directory');
		}

	}





	/**
	 * Info
	 *
	 * Generates topic information.
	 *
	 * @return array
	 */
	public function info(): array {
		$this->load->language('task/catalog/article');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$limit = 5;

		$this->load->model('cms/article');

		$article_total = $this->model_cms_article->getTotalArticles();

		$start = ($page - 1) * $limit;
		$end = $start > ($article_total - $limit) ? $article_total : ($start + $limit);

		$filter_data = [
			'start' => $start,
			'limit' => $limit
		];

		$articles = $this->model_cms_article->getArticles($filter_data);

		foreach ($articles as $article) {
			if ($article['status']) {
				$descriptions = $this->model_cms_article->getDescriptions($article['article_id']);

				$stores = $this->model_cms_article->getStores($article['article_id']);

				foreach ($descriptions as $description) {
					if (isset($languages[$description['language_id']])) {
						if (!file_put_contents($directory . 'article.' . (int)$article['article_id'] . '.' . $languages[$description['language_id']] . '.json', json_encode($description + $article))) {
							$json['error'] = $this->language->get('error_file');
						}
					}
				}
			}
		}


		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_article'), $start ?: 1, $end, $article_total);

			if ($end < $article_total) {
				$json['next'] = $this->url->link('task/catalog/article', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
			} else {
				$json['success'] = $this->language->get('text_success');
			}
		}
	}


}
