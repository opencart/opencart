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

		// Store
		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		// Language
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		// Topics
		$topics = [];

		$topics[] = 0;

		$this->load->model('cms/topic');

		$results = $this->model_cms_topic->getTopics();

		foreach ($results as $result) {
			$topics[] = $result['topic_id'];
		}

		// Sort Order
		$sorts = [];

		$sorts[] = [
			'sort'  => '',
			'order' => ''
		];

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
				foreach ($topics as $topic_id) {
					$filter_data = [
						'filter_topic_id'    => $topic_id,
						'filter_store_id'    => $store['store_id'],
						'filter_language_id' => $language['language_id'],
						'filter_status'      => true
					];

					$this->load->model('cms/article');

					$article_total = $this->model_cms_article->getTotalArticles($filter_data);

					$page_total = ceil($article_total / (int)$this->config->get('config_pagination'));

					foreach ($sorts as $sort) {
						for ($i = 1; $i <= $page_total; $i++) {
							$task_data = [
								'code'   => 'topic',
								'action' => 'task/catalog/topic.list',
								'args'   => [
									'store_id'    => $store['store_id'],
									'language_id' => $language['language_id'],
									'topic_id'    => $topic_id,
									'sort'        => $sort['sort'],
									'order'       => $sort['order'],
									'page'        => $i
								]
							];

							$this->model_setting_task->addTask($task_data);
						}
					}
				}
			}
		}

		return ['success' => $this->language->get('text_success')];
	}

	/*
	 * List
	 *
	 * @return array
	 */
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

		// Topic
		if ($args['topic_id']) {
			$this->load->model('cms/topic');

			$topic_info = $this->model_cms_topic->getTopic($args['topic_id']);

			if (!$topic_info) {
				return ['error' => $this->language->get('error_topic')];
			}
		}

		// 1. Create a store instance using loader class to call controllers, models, views, libraries.
		$this->load->model('setting/store');

		$store = $this->model_setting_store->createStoreInstance($store_info['store_id'], $language_info['code']);

		// Make sure the SEO URL's work
		$store->load->controller('startup/rewrite');

		$args['route'] = 'cms/topic';

		$keys = [
			'route',
			'topic_id',
			'language_id',
			'sort',
			'order',
			'page'
		];

		foreach ($keys as $key) {
			if (!empty($args[$key])) {
				$store->request->get[$key] = $args[$key];
			}
		}

		// 2. Call the required API controller.
		$store->load->controller('cms/topic');

		// 3. Call the required API controller and get the output.
		$output = $store->response->getOutput();

		// 4. Clean up data by clearing cart.
		$store->cart->clear();

		// 5. Deleting the current session, so we are not creating infinite sessions.
		$store->session->destroy();

		// Create the directory and file names.
		$this->load->model('design/seo_url');

		//$base = DIR_STORE;

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . $this->model_design_seo_url->convert($args['store_id'], $args['language_id'], $args) . '/';
		$filename = 'index.html';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, $output)) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * Info
	 *
	 * Generates topic information.
	 *
	 * @return array
	 */
	public function article(): array {
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
