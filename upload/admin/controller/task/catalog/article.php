<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Article
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Article extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates article task list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/article');

		$this->load->model('setting/task');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$task_data = [
					'code'   => 'article',
					'action' => 'task/catalog/article.list',
					'args'   => [
						'store_id'    => $store['store_id'],
						'language_id' => $language['language_id']
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * List
	 *
	 * Generates article list file.
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
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


	/**
	 * Info
	 *
	 * Generates article information.
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/article');

		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$this->load->model('cms/article');

		$article_info = $this->model_cms_article->getArticles((int)$args['article_id']);

		if (!$article_info) {
			return ['error' => $this->language->get('error_article')];
		}




		if ($information['status']) {
			$description_info = $this->model_catalog_information->getDescription($information['information_id'], $language_info['language_id']);

			if ($description_info) {
				$information_data[$information['information_id']] = $description_info + $information;
			}
		}



		zone_description_info = $this->model_cms_article->getDescription((int)$zone['zone_id'], (int)$language_info['language_id']);



		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/localisation/';
		$filename = 'country-' . $args['country_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($country_info + ['zone' => $zone_data]))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $language_info['name'], $country_info['name'])];

	}


	public function rating(): array {
		$this->load->language('task/catalog/article');

		return ['success' => $this->language->get('text_success')];
	}

	public function clear(): array  {
		$this->load->language('task/catalog/article');

		return ['success' => $this->language->get('text_success')];
	}
}
